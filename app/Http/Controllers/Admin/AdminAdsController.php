<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Ads\AdsProjectReportExport;
use App\Exports\Ads\AdsReportExport;
use App\Http\Controllers\Controller;
use App\Models\AdsBudget;
use App\Models\AdsCampaign;
use App\Models\AdsSpend;
use App\Repo\AdminRepo;
use App\Repo\AdsBudgetRepo;
use App\Repo\AdsCampaignRepo;
use App\Repo\AdsSpendRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminAdsController extends Controller
{
    // slug role "Ads" trong DB hiện đang là "ADS" (viết hoa) nên check cả 2 dạng cho chắc
    const ROLES = ['super_admin', 'ceo', 'ads', 'ADS', 'content', 'account'];

    private $projectRepo;
    private $phase;
    private $role;
    private $campaign;
    private $budget;
    private $spend;
    private $admin;

    public function __construct(
        ProjectRepo $projectRepo,
        PhaseRepo $phase,
        RoleRepo $role,
        AdsCampaignRepo $campaign,
        AdsBudgetRepo $budget,
        AdsSpendRepo $spend,
        AdminRepo $admin
    ) {
        $this->projectRepo = $projectRepo;
        $this->phase = $phase;
        $this->role = $role;
        $this->campaign = $campaign;
        $this->budget = $budget;
        $this->spend = $spend;
        $this->admin = $admin;
    }

    private function checkPermission()
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(self::ROLES)) {
            return null;
        }
        return $user;
    }

    private function filterBudgets($budgets, $startTime = false, $endTime = false)
    {
        return $budgets->when($startTime, function ($q) use ($startTime) {
            return $q->where('entered_time', '>=', $startTime);
        })->when($endTime, function ($q) use ($endTime) {
            return $q->where('entered_time', '<=', $endTime);
        });
    }

    private function filterSpends($spends, $startTime = false, $endTime = false)
    {
        return $spends->when($startTime, function ($q) use ($startTime) {
            return $q->where('spend_date', '>=', date('Y-m-d', $startTime));
        })->when($endTime, function ($q) use ($endTime) {
            return $q->where('spend_date', '<=', date('Y-m-d', $endTime));
        });
    }

    /**
     * Ngân sách nhập theo dự án; chi tiêu của mọi chiến dịch trong dự án trừ vào ngân sách tổng đó.
     * Trả về [project_id => [total_budget, total_spend, remaining]]
     */
    private function projectTotals($projectIds, $startTime = false, $endTime = false)
    {
        $projectIds = collect($projectIds)->filter()->unique()->values();
        if ($projectIds->isEmpty()) {
            return collect([]);
        }

        $budgets = AdsBudget::whereIn('project_id', $projectIds)
            ->when($startTime, function ($q) use ($startTime) {
                return $q->where('entered_time', '>=', $startTime);
            })->when($endTime, function ($q) use ($endTime) {
                return $q->where('entered_time', '<=', $endTime);
            })
            ->groupBy('project_id')
            ->selectRaw('project_id, SUM(amount) as total')
            ->pluck('total', 'project_id');

        $spends = AdsSpend::join('ads_campaign', 'ads_campaign.id', '=', 'ads_spend.campaign_id')
            ->whereIn('ads_campaign.project_id', $projectIds)
            ->when($startTime, function ($q) use ($startTime) {
                return $q->where('ads_spend.spend_date', '>=', date('Y-m-d', $startTime));
            })->when($endTime, function ($q) use ($endTime) {
                return $q->where('ads_spend.spend_date', '<=', date('Y-m-d', $endTime));
            })
            ->groupBy('ads_campaign.project_id')
            ->selectRaw('ads_campaign.project_id, SUM(ads_spend.amount) as total')
            ->pluck('total', 'project_id');

        return $projectIds->mapWithKeys(function ($projectId) use ($budgets, $spends) {
            $totalBudget = (int) ($budgets[$projectId] ?? 0);
            $totalSpend = (int) ($spends[$projectId] ?? 0);
            return [$projectId => [
                'total_budget' => $totalBudget,
                'total_spend' => $totalSpend,
                'remaining' => $totalBudget - $totalSpend,
            ]];
        });
    }

    private function buildReport($campaign, $startTime = false, $endTime = false)
    {
        $spends = $this->filterSpends($campaign->spend, $startTime, $endTime)->sortByDesc('spend_date')->values();
        $project = $this->projectTotals([$campaign->project_id])->get($campaign->project_id);

        return [
            'campaign' => $campaign,
            'spends' => $spends,
            'total_spend' => $spends->sum('amount'),
            'total_results' => $spends->sum('results'),
            'project_budget' => $project['total_budget'] ?? 0,
            'project_spend' => $project['total_spend'] ?? 0,
            'project_remaining' => $project['remaining'] ?? 0,
        ];
    }

    public function index(Request $request, $id, $pid = 0)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền truy cập mục quản lý Ads!');
        }

        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $gid = 0;
        $project = $this->projectRepo->first(['id' => $id], [], ['group']);
        if (empty($project)) {
            return back()->with('error_message', 'Không tìm thấy dự án!');
        }

        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'], ['phaseGroup', 'group'])->keyBy('id');
        $pid = $pid > 0 ? $pid : ($phase->first() ? $phase->first()->id : 0);
        $role = $this->role->getRole();

        $campaigns = $this->campaign->get(['project_id' => $id], ['id' => 'DESC'], ['spend', 'handler'])
            ->map(function ($campaign) {
                $campaign->total_spend = $campaign->spend->sum('amount');
                $campaign->total_results = $campaign->spend->sum('results');
                return $campaign;
            });

        $budgets = $this->budget->get(['project_id' => $id], ['entered_time' => 'DESC'], ['creator']);
        $totalBudget = $budgets->sum('amount');
        $totalSpend = $campaigns->sum('total_spend');
        $remaining = $totalBudget - $totalSpend;

        $admins = $this->admin->get();
        $channels = AdsCampaign::CHANNELS;

        return view('admin.ads.index', compact('project', 'phase', 'pid', 'isAdmin', 'gid', 'role', 'campaigns', 'admins', 'channels', 'budgets', 'totalBudget', 'totalSpend', 'remaining'));
    }

    public function createCampaign(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $id = $request->input('id');
        $params = $request->only('name', 'project_id', 'channel', 'product_link', 'handler_id', 'ad_account', 'payment_card');
        $params['handler_id'] = $params['handler_id'] ?: null;
        $params['start_time'] = $request->input('start_time') ? strtotime($request->input('start_time')) : null;
        $params['end_time'] = $request->input('end_time') ? strtotime($request->input('end_time')) : null;

        if ($id) {
            $campaign = $this->campaign->first(['id' => $id]);
            if (empty($campaign)) {
                return back()->with('error_message', 'Không tìm thấy chiến dịch!');
            }
            $params['updated_by'] = $user->id;
            $this->campaign->update($campaign, $params);
            return back()->with('success_message', 'Cập nhật chiến dịch thành công!');
        }

        $params['created_by'] = $user->id;
        $this->campaign->create($params);
        return back()->with('success_message', 'Tạo chiến dịch thành công!');
    }

    public function removeCampaign(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        $id = $request->input('id');
        $this->campaign->remove($id);
        return response()->json(['success' => 1]);
    }

    public function createBudget(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        $id = $request->input('id');
        $params = $request->only('project_id', 'amount', 'note');
        if (empty($params['project_id']) || !$this->projectRepo->first(['id' => $params['project_id']])) {
            return response()->json(['success' => 0, 'message' => 'Không tìm thấy dự án!']);
        }
        $params['entered_time'] = $request->input('entered_time') ? strtotime($request->input('entered_time')) : time();

        if ($id) {
            $budget = $this->budget->first(['id' => $id]);
            if (empty($budget)) {
                return response()->json(['success' => 0, 'message' => 'Không tìm thấy khoản ngân sách!']);
            }
            $params['updated_by'] = $user->id;
            $this->budget->update($budget, $params);
            return response()->json(['success' => 1, 'message' => 'Cập nhật ngân sách thành công!']);
        }

        $params['created_by'] = $user->id;
        $this->budget->create($params);
        return response()->json(['success' => 1, 'message' => 'Nhập ngân sách thành công!']);
    }

    public function removeBudget(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        $id = $request->input('id');
        $this->budget->remove($id);
        return response()->json(['success' => 1]);
    }

    public function createSpend(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        $id = $request->input('id');
        $params = $request->only('campaign_id', 'spend_date', 'amount', 'results', 'note');

        if ($id) {
            $spend = $this->spend->first(['id' => $id]);
            if (empty($spend)) {
                return response()->json(['success' => 0, 'message' => 'Không tìm thấy chi tiêu!']);
            }
            $params['updated_by'] = $user->id;
            $this->spend->update($spend, $params);
            return response()->json(['success' => 1, 'message' => 'Cập nhật chi tiêu thành công!']);
        }

        $params['created_by'] = $user->id;
        $this->spend->create($params);
        return response()->json(['success' => 1, 'message' => 'Nhập chi tiêu thành công!']);
    }

    public function removeSpend(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền thực hiện thao tác này!']);
        }

        $id = $request->input('id');
        $this->spend->remove($id);
        return response()->json(['success' => 1]);
    }

    public function report(Request $request, $campaign_id)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $campaign = $this->campaign->first(['id' => $campaign_id], [], ['spend']);
        if (empty($campaign)) {
            return response()->json(['success' => 0, 'message' => 'Không tìm thấy chiến dịch!']);
        }

        $startTime = $request->get('start_time', '') ? strtotime($request->get('start_time', '')) : false;
        $endTime = $request->get('end_time', '') ? strtotime('tomorrow', strtotime($request->get('end_time', ''))) - 1 : false;

        return response()->json(['success' => 1, 'data' => $this->buildReport($campaign, $startTime, $endTime)]);
    }

    public function export(Request $request, $campaign_id)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $campaign = $this->campaign->first(['id' => $campaign_id], [], ['spend.creator', 'project']);
        if (empty($campaign)) {
            return back()->with('error_message', 'Không tìm thấy chiến dịch!');
        }

        $startDate = $request->get('start_time', '');
        $endDate = $request->get('end_time', '');
        $startTime = $startDate ? strtotime($startDate) : false;
        $endTime = $endDate ? strtotime('tomorrow', strtotime($endDate)) - 1 : false;

        $report = $this->buildReport($campaign, $startTime, $endTime);
        $report['start_date'] = $startDate;
        $report['end_date'] = $endDate;

        $fileName = 'Bao-cao-ads-' . $campaign->name . ($startDate || $endDate ? '-' . ($startDate ?: 'truoc') . '_' . ($endDate ?: 'nay') : '') . '.xlsx';
        return Excel::download(new AdsReportExport($report), $fileName);
    }

    public function exportProject(Request $request, $project_id)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $project = $this->projectRepo->first(['id' => $project_id]);
        if (empty($project)) {
            return back()->with('error_message', 'Không tìm thấy dự án!');
        }

        $budgets = $this->budget->get(['project_id' => $project_id], ['entered_time' => 'DESC']);
        $campaigns = $this->campaign->get(['project_id' => $project_id], ['id' => 'DESC'], ['spend'])
            ->map(function ($campaign) {
                $campaign->total_spend = $campaign->spend->sum('amount');
                $campaign->total_results = $campaign->spend->sum('results');
                return $campaign;
            });
        $totalBudget = $budgets->sum('amount');
        $totalSpend = $campaigns->sum('total_spend');

        $report = [
            'project' => $project,
            'budgets' => $budgets,
            'campaigns' => $campaigns,
            'total_budget' => $totalBudget,
            'total_spend' => $totalSpend,
            'remaining' => $totalBudget - $totalSpend,
        ];

        return Excel::download(new AdsProjectReportExport($report), 'Bao-cao-ads-du-an-' . $project->name . '.xlsx');
    }

    public function dashboardReport(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $projectId = $request->get('project_id', 0);
        $startTime = $request->get('start_time', '') ? strtotime($request->get('start_time', '')) : false;
        $endTime = $request->get('end_time', '') ? strtotime('tomorrow', strtotime($request->get('end_time', ''))) - 1 : false;

        $condition = [];
        if ($projectId) {
            $condition['project_id'] = $projectId;
        }

        $campaigns = $this->campaign->get($condition, ['id' => 'DESC'], ['project', 'spend']);
        $projectTotals = $this->projectTotals($campaigns->pluck('project_id'), $startTime, $endTime);

        $data = $campaigns->map(function ($campaign) use ($startTime, $endTime) {
            return [
                'project' => $campaign->project->name ?? '',
                'project_id' => $campaign->project_id,
                'campaign' => $campaign->name,
                'total_spend' => $this->filterSpends($campaign->spend, $startTime, $endTime)->sum('amount'),
            ];
        })->groupBy('project_id')->map(function ($items, $projectId) use ($projectTotals) {
            $totals = $projectTotals->get($projectId);
            return [
                'project' => $items->first()['project'],
                'campaigns' => $items->values(),
                'total_budget' => $totals['total_budget'] ?? 0,
                'total_spend' => $totals['total_spend'] ?? 0,
                'remaining' => $totals['remaining'] ?? 0,
            ];
        })->values();

        return response()->json(['success' => 1, 'data' => $data]);
    }

    public function handlerReport(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $handlerId = $request->get('handler_id', 0);
        $projectId = $request->get('project_id', 0);
        $startTime = $request->get('start_time', '') ? strtotime($request->get('start_time', '')) : false;
        $endTime = $request->get('end_time', '') ? strtotime('tomorrow', strtotime($request->get('end_time', ''))) - 1 : false;

        $condition = [];
        if ($projectId) {
            $condition['project_id'] = $projectId;
        }
        if ($handlerId) {
            $condition['handler_id'] = $handlerId;
        }

        $campaigns = $this->campaign->get($condition, ['id' => 'DESC'], ['project', 'handler', 'spend']);
        // Ngân sách/còn dư tính theo cả dự án (mọi chiến dịch), không riêng chiến dịch của người xử lý
        $projectTotals = $this->projectTotals($campaigns->pluck('project_id'), $startTime, $endTime);

        $data = $campaigns->map(function ($campaign) use ($startTime, $endTime) {
            $spends = $this->filterSpends($campaign->spend, $startTime, $endTime);

            return [
                'handler_id' => $campaign->handler_id ?: 0,
                'handler' => $campaign->handler->username ?? 'Chưa phân công',
                'project_id' => $campaign->project_id,
                'project' => $campaign->project->name ?? '',
                'campaign_id' => $campaign->id,
                'campaign' => $campaign->name,
                'total_spend' => $spends->sum('amount'),
                'total_results' => $spends->sum('results'),
            ];
        })->groupBy('handler_id')->map(function ($items, $handlerId) use ($projectTotals) {
            $projects = $items->groupBy('project_id')->map(function ($rows, $projectId) use ($projectTotals) {
                $totals = $projectTotals->get($projectId);
                return [
                    'project' => $rows->first()['project'],
                    'project_id' => $projectId,
                    'campaigns' => $rows->values(),
                    'total_budget' => $totals['total_budget'] ?? 0,
                    'total_spend' => $rows->sum('total_spend'),
                    'total_results' => $rows->sum('total_results'),
                    'remaining' => $totals['remaining'] ?? 0,
                ];
            })->values();

            $rowAllProject = [
                'project' => 'Tất cả dự án',
                'project_id' => 0,
                'campaigns' => collect([]),
                'total_budget' => $projects->sum('total_budget'),
                'total_spend' => $items->sum('total_spend'),
                'total_results' => $items->sum('total_results'),
                'remaining' => $projects->sum('remaining'),
            ];
            $projects->prepend($rowAllProject);

            return [
                'handler' => $items->first()['handler'],
                'projects' => $projects,
            ];
        })->values();

        return response()->json(['success' => 1, 'data' => $data]);
    }

    // Ngưỡng cảnh báo "sắp hết ngân sách": còn dư <= 500.000đ (và chưa âm)
    const BUDGET_ALERT_THRESHOLD = 500000;

    public function budgetAlertReport(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $month = $request->get('month', date('Y-m'));
        $account = trim($request->get('account', ''));
        $startTime = strtotime($month . '-01 00:00:00');
        $endTime = strtotime(date('Y-m-t 23:59:59', $startTime));

        $campaigns = $this->campaign->get(
            $account !== '' ? ['ad_account' => $account] : [],
            ['id' => 'DESC'],
            ['project', 'spend']
        );

        $campaignRows = $campaigns->map(function ($campaign) use ($startTime, $endTime) {
            return [
                'project_id' => $campaign->project_id,
                'project' => $campaign->project->name ?? '',
                'campaign_id' => $campaign->id,
                'campaign' => $campaign->name,
                'ad_account' => $campaign->ad_account ?: '',
                'total_spend' => $this->filterSpends($campaign->spend, $startTime, $endTime)->sum('amount'),
            ];
        });

        // Dự án cần xét: có chiến dịch (theo tài khoản nếu có lọc); không lọc tài khoản thì gồm cả dự án chỉ mới nhập ngân sách
        $projectIds = $campaignRows->pluck('project_id');
        if ($account === '') {
            $projectIds = $projectIds->merge(
                AdsBudget::whereBetween('entered_time', [$startTime, $endTime])->whereNotNull('project_id')->pluck('project_id')
            );
        }
        $projectIds = $projectIds->unique()->values();
        $projectTotals = $this->projectTotals($projectIds, $startTime, $endTime);
        $projectNames = $this->projectRepo->get(['id' => $projectIds->all()])->pluck('name', 'id');
        $campaignsByProject = $campaignRows->groupBy('project_id');

        // Ngân sách nhập theo dự án; còn dư = ngân sách dự án - chi tiêu mọi chiến dịch của dự án
        $projects = $projectIds->map(function ($projectId) use ($projectTotals, $projectNames, $campaignsByProject) {
            $totals = $projectTotals->get($projectId);
            $campaigns = $campaignsByProject->get($projectId, collect([]))->values();
            return [
                'project_id' => $projectId,
                'project' => $projectNames[$projectId] ?? '',
                'campaigns' => $campaigns,
                'count' => $campaigns->count(),
                'total_budget' => $totals['total_budget'] ?? 0,
                'total_spend' => $totals['total_spend'] ?? 0,
                'remaining' => $totals['remaining'] ?? 0,
            ];
        });

        $nearLimit = $projects->filter(function ($r) {
            return $r['total_budget'] > 0 && $r['remaining'] >= 0 && $r['remaining'] <= self::BUDGET_ALERT_THRESHOLD;
        })->values();
        $overBudget = $projects->filter(function ($r) {
            return $r['remaining'] < 0;
        })->values();
        $budgetDetail = $projects->filter(function ($r) {
            return $r['total_budget'] > 0;
        })->values();
        $spendDetail = $projects->map(function ($r) {
            $r['campaigns'] = $r['campaigns']->filter(function ($c) {
                return $c['total_spend'] > 0;
            })->values();
            $r['count'] = $r['campaigns']->count();
            return $r;
        })->filter(function ($r) {
            return $r['count'] > 0;
        })->values();

        $accountDetail = $campaignRows->filter(function ($r) {
            return $r['total_spend'] > 0;
        })->groupBy('ad_account')->map(function ($rows, $adAccount) {
            return [
                'ad_account' => $adAccount !== '' ? $adAccount : '(Không có tài khoản)',
                'campaigns' => $rows->values(),
                'count' => $rows->count(),
                'total_spend' => $rows->sum('total_spend'),
            ];
        })->values()->sortByDesc('total_spend')->values();

        return response()->json([
            'success' => 1,
            'data' => [
                'total_budget' => $projects->sum('total_budget'),
                'total_spend' => $campaignRows->sum('total_spend'),
                'near_limit_count' => $nearLimit->count(),
                'over_budget_count' => $overBudget->count(),
                'near_limit' => $nearLimit,
                'over_budget' => $overBudget,
                'budget_detail' => $budgetDetail,
                'spend_detail' => $spendDetail,
                'account_detail' => $accountDetail,
            ],
        ]);
    }

    public function accountList(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $accounts = AdsCampaign::whereNotNull('ad_account')
            ->where('ad_account', '!=', '')
            ->distinct()
            ->orderBy('ad_account')
            ->pluck('ad_account');

        return response()->json(['success' => 1, 'data' => $accounts]);
    }

    public function accountReport(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return response()->json(['success' => 0, 'message' => 'Bạn không có quyền truy cập mục này!']);
        }

        $account = trim($request->get('account', ''));
        if ($account === '') {
            return response()->json(['success' => 0, 'message' => 'Vui lòng chọn tài khoản quảng cáo!']);
        }

        $campaigns = $this->campaign->get(['ad_account' => $account], ['id' => 'DESC'], ['project']);

        $totalProjects = $campaigns->pluck('project_id')->unique()->count();
        $totalCards = $campaigns->pluck('payment_card')->filter(function ($v) {
            return !empty($v);
        })->unique()->count();

        $cards = $campaigns->groupBy(function ($c) {
            return $c->payment_card ?: '';
        })->map(function ($items, $card) {
            $projects = $items->groupBy('project_id')->map(function ($rows, $projectId) {
                return [
                    'project_id' => $projectId,
                    'project' => $rows->first()->project->name ?? '',
                    'campaigns' => $rows->map(function ($c) {
                        return ['campaign_id' => $c->id, 'campaign' => $c->name];
                    })->values(),
                ];
            })->values();

            return [
                'card' => $card !== '' ? $card : '(Không có thẻ)',
                'total_projects' => $projects->count(),
                'projects' => $projects,
            ];
        })->values();

        return response()->json([
            'success' => 1,
            'data' => [
                'account' => $account,
                'total_cards' => $totalCards,
                'total_projects' => $totalProjects,
                'cards' => $cards,
            ],
        ]);
    }
}

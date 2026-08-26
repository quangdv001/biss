<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Ads\AdsReportExport;
use App\Http\Controllers\Controller;
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
    const ROLES = ['super_admin', 'ceo', 'ads', 'ADS', 'content'];

    private $projectRepo;
    private $phase;
    private $role;
    private $campaign;
    private $budget;
    private $spend;

    public function __construct(
        ProjectRepo $projectRepo,
        PhaseRepo $phase,
        RoleRepo $role,
        AdsCampaignRepo $campaign,
        AdsBudgetRepo $budget,
        AdsSpendRepo $spend
    ) {
        $this->projectRepo = $projectRepo;
        $this->phase = $phase;
        $this->role = $role;
        $this->campaign = $campaign;
        $this->budget = $budget;
        $this->spend = $spend;
    }

    private function checkPermission()
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(self::ROLES)) {
            return null;
        }
        return $user;
    }

    private function buildReport($campaign)
    {
        $budgets = $campaign->budget->sortByDesc('entered_time')->values();
        $spends = $campaign->spend->sortByDesc('spend_date')->values();
        $totalBudget = $budgets->sum('amount');
        $totalSpend = $spends->sum('amount');

        return [
            'campaign' => $campaign,
            'budgets' => $budgets,
            'spends' => $spends,
            'total_budget' => $totalBudget,
            'total_spend' => $totalSpend,
            'remaining' => $totalBudget - $totalSpend,
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

        $campaigns = $this->campaign->get(['project_id' => $id], ['id' => 'DESC'], ['budget', 'spend', 'creator', 'editor'])
            ->map(function ($campaign) {
                $campaign->total_budget = $campaign->budget->sum('amount');
                $campaign->total_spend = $campaign->spend->sum('amount');
                $campaign->remaining = $campaign->total_budget - $campaign->total_spend;
                return $campaign;
            });

        $campaignId = $request->get('campaign', 0);
        $activeCampaign = $campaignId ? $campaigns->firstWhere('id', (int) $campaignId) : $campaigns->first();
        $report = $activeCampaign ? $this->buildReport($activeCampaign) : null;

        return view('admin.ads.index', compact('project', 'phase', 'pid', 'isAdmin', 'gid', 'role', 'campaigns', 'activeCampaign', 'report'));
    }

    public function createCampaign(Request $request)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $id = $request->input('id');
        $params = $request->only('name', 'project_id');
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
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $id = $request->input('id');
        $params = $request->only('campaign_id', 'amount', 'note');
        $params['entered_time'] = $request->input('entered_time') ? strtotime($request->input('entered_time')) : time();

        if ($id) {
            $budget = $this->budget->first(['id' => $id]);
            if (empty($budget)) {
                return back()->with('error_message', 'Không tìm thấy khoản ngân sách!');
            }
            $params['updated_by'] = $user->id;
            $this->budget->update($budget, $params);
            return back()->with('success_message', 'Cập nhật ngân sách thành công!');
        }

        $params['created_by'] = $user->id;
        $this->budget->create($params);
        return back()->with('success_message', 'Nhập ngân sách thành công!');
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
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $id = $request->input('id');
        $params = $request->only('campaign_id', 'spend_date', 'amount', 'product_link', 'note');

        if ($id) {
            $spend = $this->spend->first(['id' => $id]);
            if (empty($spend)) {
                return back()->with('error_message', 'Không tìm thấy chi tiêu!');
            }
            $params['updated_by'] = $user->id;
            $this->spend->update($spend, $params);
            return back()->with('success_message', 'Cập nhật chi tiêu thành công!');
        }

        $params['created_by'] = $user->id;
        $this->spend->create($params);
        return back()->with('success_message', 'Nhập chi tiêu thành công!');
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

        $campaign = $this->campaign->first(['id' => $campaign_id], [], ['budget', 'spend']);
        if (empty($campaign)) {
            return response()->json(['success' => 0, 'message' => 'Không tìm thấy chiến dịch!']);
        }

        return response()->json(['success' => 1, 'data' => $this->buildReport($campaign)]);
    }

    public function export($campaign_id)
    {
        $user = $this->checkPermission();
        if (!$user) {
            return back()->with('error_message', 'Bạn không có quyền thực hiện thao tác này!');
        }

        $campaign = $this->campaign->first(['id' => $campaign_id], [], ['budget.creator', 'spend.creator', 'project']);
        if (empty($campaign)) {
            return back()->with('error_message', 'Không tìm thấy chiến dịch!');
        }

        $report = $this->buildReport($campaign);
        return Excel::download(new AdsReportExport($report), 'Bao-cao-ads-' . $campaign->name . '.xlsx');
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

        $campaigns = $this->campaign->get($condition, ['id' => 'DESC'], ['project', 'budget', 'spend']);

        $data = $campaigns->map(function ($campaign) use ($startTime, $endTime) {
            $budgets = $campaign->budget->when($startTime, function ($q) use ($startTime) {
                return $q->where('entered_time', '>=', $startTime);
            })->when($endTime, function ($q) use ($endTime) {
                return $q->where('entered_time', '<=', $endTime);
            });
            $spends = $campaign->spend->when($startTime, function ($q) use ($startTime) {
                return $q->where('spend_date', '>=', date('Y-m-d', $startTime));
            })->when($endTime, function ($q) use ($endTime) {
                return $q->where('spend_date', '<=', date('Y-m-d', $endTime));
            });

            $totalBudget = $budgets->sum('amount');
            $totalSpend = $spends->sum('amount');

            return [
                'project' => $campaign->project->name ?? '',
                'project_id' => $campaign->project_id,
                'campaign' => $campaign->name,
                'total_budget' => $totalBudget,
                'total_spend' => $totalSpend,
                'remaining' => $totalBudget - $totalSpend,
            ];
        })->groupBy('project_id')->map(function ($items, $projectId) {
            return [
                'project' => $items->first()['project'],
                'campaigns' => $items->values(),
                'total_budget' => $items->sum('total_budget'),
                'total_spend' => $items->sum('total_spend'),
                'remaining' => $items->sum('remaining'),
            ];
        })->values();

        return response()->json(['success' => 1, 'data' => $data]);
    }
}

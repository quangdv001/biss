<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    private $role;
    private $projectRepo;
    private $ticketRepo;
    public function __construct(RoleRepo $role,ProjectRepo $projectRepo,TicketRepo $ticketRepo)
    {
        $this->role = $role;
        $this->projectRepo = $projectRepo;
        $this->ticketRepo = $ticketRepo;
    }

    public function index(){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý chức vụ!');
        }
        $data = $this->role->get();
        return view('admin.role.index', compact('data'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý chức vụ!');
        }
        $params = $request->only('id', 'name', 'slug');
        if(isset($params['id'])){
            $role = $this->role->first(['id' => $params['id']]);
            if($role){
                $res = $this->role->update($role, $params);
                if($res){
                    return back()->with('success_message', 'Cập nhật chức vụ thành công!');
                }
            }
        } else {
            $res = $this->role->create($params);
            if($res){
                return back()->with('success_message', 'Tạo chức vụ thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return response(['success' => 0]);
        }
        $id = $request->input('id');
        $resR = $this->role->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }

    public function report(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin', 'account'])) {
            return response(['success' => 0, 'message' => 'Bạn không có quyền quản lý tài khoản']);
        }
        $id = $request->get('id', 0);
        $project_id = $request->get('project_id', 0);
        $admin_id = $request->get('admin_id', 0);
        $role = $this->role->first(['id' => $id]);
        if (empty($role)) {
            return response(['success' => 0, 'message' => 'Không tìm thấy chức vụ!']);
        }
        $admin = $role->admin->keyBy('id')->all();
        $admin_ids = array_keys($admin);
        $start_time = strtotime($request->get('start_time', ''));
        $end_time = strtotime($request->get('end_time', ''));
        $end_time = $end_time ? strtotime('tomorrow', $end_time) - 1 : false;

        $tickets = $this->ticketRepo->getTicketByAdmin($admin_ids, $project_id, $start_time, $end_time);
        $project = $this->projectRepo->get(['id' => $tickets->pluck('project_id')->all()])->load('admin')->map(function ($project) {
            $project->admin_ids = $project->admin->pluck('id')->all();
            return $project;
        })->keyBy('id')->all();
        $data = $tickets->map(function ($ticket) {
            $ticket->admin_id = $ticket->admin->pluck('id')->all();
            return $ticket;
        })->groupBy(['admin_id', function ($item) {
            return $item['project_id'];
        }], true)->map(function ($projects, $admin_id) use ($admin, $project) {
            $data['admin'] = @$admin[$admin_id]['username'];
            $data['projects'] = $projects->map(function ($tickets, $project_id) use ($project, $admin_id) {
                $data['project'] = @$project[$project_id]['name'];
                $data['report']['total'] = 0;
                $data['report']['new'] = 0;
                $data['report']['expired'] = 0;
                $data['report']['done'] = 0;
                $data['report']['done_on_time'] = 0;
                $data['report']['done_out_time'] = 0;
                $data['report']['percent_on_time'] = 0;
                $data['report']['percent_out_time'] = 0;
                // $data['report']['qty'] = 0;
                $phaseGroupIds  = [];
                if (!empty($tickets)) {
                    foreach ($tickets as $ticket) {
                        $qty = $ticket['qty'] ?? 1;
                        $data['report']['total'] += $qty;
                        if ($ticket['status'] == 0) {
                            if (time() > $ticket['deadline_time']) {
                                $data['report']['expired'] += $qty;
                            } else {
                                $data['report']['new'] += $qty;
                            }
                        }
                        if ($ticket['status'] == 1) {
                            $data['report']['done'] += $qty;
                            if ($ticket['complete_time'] <= $ticket['deadline_time']) {
                                $data['report']['done_on_time'] += $qty;
                            } else {
                                $data['report']['done_out_time'] += $qty;
                            }
                        }
                        // $phaseGroupId = $ticket['group_id'] . '_' . $ticket['phase_id'];
                        // if (!in_array($phaseGroupId, $phaseGroupIds) && in_array($admin_id, $project[$project_id]['admin_ids'] ?? [])) {
                        //     $phaseGroup = ($ticket->group->phaseGroup ?? collect([]))->where('group_id', $ticket['group_id'])->where('phase_id', $ticket['phase_id'])->first();
                        //     $data['report']['qty'] += $phaseGroup->qty ?? 0;
                        //     $phaseGroupIds[] = $phaseGroupId;
                        // }
                    }
                }
                $data['report']['percent_on_time'] = $data['report']['done'] > 0 ? round($data['report']['done_on_time'] / $data['report']['done'] * 100) : 0;
                $data['report']['percent_out_time'] = $data['report']['done'] > 0 ? round($data['report']['done_out_time'] / $data['report']['done'] * 100) : 0;
                return $data;
            })->values()->all();
            return $data;
        })->all();
        $data = collect($admin)->filter(function ($admin) use ($admin_id){
            return empty($admin_id) || $admin_id == $admin->id;
        })->map(function ($admin,$admin_id) use ($data){
            $item['admin'] = $admin->username ?? '';
            $item['projects'] = $data[$admin_id]['projects'] ?? [];
            if(!empty($item['projects'])){
                $total = collect($item['projects'])->sum('report.total');
                // $qty = collect($item['projects'])->sum('report.qty');
                $new = collect($item['projects'])->sum('report.new');
                $expired = collect($item['projects'])->sum('report.expired');
                $done = collect($item['projects'])->sum('report.done');
                $done_on_time = collect($item['projects'])->sum('report.done_on_time');
                $done_out_time = collect($item['projects'])->sum('report.done_out_time');
                $percent_on_time = $done > 0 ? round(($done_on_time/$done)*100) : 0;
                $percent_out_time = $done > 0 ? round(($done_out_time/$done)*100) : 0;
                // $percent = $qty ? round($done / $qty * 100) : 0;
                $rowAllProject = [
                    'project' => 'Tất cả dự án',
                    'report' => ['total' => $total, 'new' => $new, 'expired' => $expired, 'done' => $done, 'done_on_time' => $done_on_time, 'done_out_time' => $done_out_time, 'percent_on_time' => $percent_on_time, 'percent_out_time' => $percent_out_time]
                ];
                array_unshift($item['projects'], $rowAllProject);
            }
            return $item;
        })->values()->all();
        // dd($data);
        return response(['success' => 1, 'data' => $data, 'project' => array_values($project) ,'admin' =>array_values($admin)]);
    }

    public function report2(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin', 'account'])) {
            return response(['success' => 0, 'message' => 'Bạn không có quyền quản lý tài khoản']);
        }
        $id = $request->get('id', 0);
        $admin_id = $request->get('admin_id', 0);
        $role = $this->role->first(['id' => $id]);
        if (empty($role)) {
            return response(['success' => 0, 'message' => 'Không tìm thấy chức vụ!']);
        }
        $admin = $role->admin->keyBy('id')->all();
        $arrAdmin = $admin_id ? [$admin_id] : array_keys($admin);
        $params['start_time'] = $request->get('start_time', '') ? strtotime($request->get('start_time', '')) : time() - 2592000;
        $params['end_time'] = $request->get('end_time', '') ? strtotime($request->get('end_time', '')) : time();
        $projects = $this->projectRepo->getProjectReport($params);
        $temp = $projects->map(function ($project) {
            $project->admin_id = $project->admin->pluck('id')->all();
            return $project;
        })->groupBy('admin_id');
        $tickets = $this->ticketRepo->getTicketByAdmin($temp->keys(), 0, $params['start_time'], $params['end_time']);
        $tickets = $tickets->where('status', 1)->map(function ($ticket) {
            $ticket->admin_id = $ticket->admin->pluck('id')->all();
            return $ticket;
        })->groupBy(['admin_id', function ($item) {
            return $item['group_id'];
        }], true);
        $data = [];
        if($temp->count() > 0){
            foreach($temp as $k => $v){
                if(in_array($k, $arrAdmin)){

                    $proj = [];
                    if($v->count() > 0){
                        foreach($v as $val){
                            $proj[] = [
                                'name' => $val->name,
                                'qty' => $val->group->where('role_id', $id)->first() ? ceil(($val->group->where('role_id', $id)->first()->phaseGroup->sortByDesc('phase_id')->first()->qty)/($val->payment_month ? Str::replace(',', '.', $val->payment_month) : 1)) : 0,
                                'complete' => isset($tickets[$k][$val->group->where('role_id', $id)->first()->id]) ? $tickets[$k][$val->group->where('role_id', $id)->first()->id]->count() : 0
                            ];
                        }
                    }
                    $data[] = [
                        'admin' => @$admin[$k]['username'],
                        'projects' => $proj,
                        'total' => collect($proj)->sum('qty'),
                        'total_complete' => collect($proj)->sum('complete'),
                    ];
                }
            }
        }
        return response(['success' => 1, 'data' => $data ,'admin' =>array_values($admin)]);
    }
}

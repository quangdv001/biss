<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

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
        $id = $request->get('id', '3');
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
        $project = $this->projectRepo->get([])->keyBy('id')->all();
        $data = $this->ticketRepo->getTicketByAdmin($admin_ids, $project_id, $start_time, $end_time)->map(function ($ticket) {
            $ticket->admin_id = $ticket->admin->pluck('id')->all();
            return $ticket;
        })->groupBy(['admin_id', function ($item) {
            return $item['project_id'];
        }], true)->map(function ($projects, $admin_id) use ($admin, $project) {
            $data['admin'] = @$admin[$admin_id]['username'];
            $data['projects'] = $projects->map(function ($tickets, $project_id) use ($project) {
                $data['project'] = @$project[$project_id]['name'];
                $data['report']['total'] = 0;
                $data['report']['new'] = 0;
                $data['report']['expired'] = 0;
                $data['report']['done'] = 0;
                $data['report']['done_on_time'] = 0;
                $data['report']['done_out_time'] = 0;
                $data['report']['percent'] = 0;
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
                    }
                }
                $data['report']['percent'] = !empty($data['report']['total']) ? round($data['report']['done'] / $data['report']['total'] * 100) : 0;
                return $data;
            })->values()->all();
            return $data;
        })->all();
        $data = collect($admin)->filter(function ($admin) use ($admin_id){
            return empty($admin_id) || $admin_id == $admin->id;
        })->map(function ($admin,$admin_id) use ($data){
            $item['admin'] = $admin->username ?? '';
            $item['projects'] = $data[$admin_id]['projects'] ?? [];
            return $item;
        })->values()->all();
        return response(['success' => 1, 'data' => $data, 'project' => array_values($project) ,'admin' =>array_values($admin)]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    private $admin, $role;
    private $ticketRepo;
    private $projectRepo;
    public function __construct(AdminRepo $admin, RoleRepo $role, TicketRepo $ticketRepo, ProjectRepo $projectRepo)
    {
        $this->admin = $admin;
        $this->role = $role;
        $this->ticketRepo = $ticketRepo;
        $this->projectRepo = $projectRepo;
    }

    public function index(){
        $data = $this->admin->get([],['id' => 'DESC'], ['roles']);
        $roles = $this->role->get();
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin', 'account'])) {
            return back()->with('error_message', 'Bạn không có quyền quản lý tài khoản!');
        }
        return view('admin.account.index', compact('data', 'roles'));
    }

    public function create(Request $request){
        $params = $request->only('id', 'username', 'password', 'status', 'name', 'phone', 'address', 'email', 'mns', 'birthday');
        $params['birthday'] = $params['birthday'] ? strtotime($params['birthday']) : null;
        $params['status'] = isset($params['status']) ? 1 : 0;
        $roles = $request->input('roles', []);
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý tài khoản!');
        }
        if(isset($params['id'])){
            $admin = $this->admin->first(['id' => $params['id']]);
            if($admin){
                $res = $this->admin->update($admin, $params);
                if($res){
                    $res->roles()->sync($roles);
                    return back()->with('success_message', 'Cập nhật thành viên thành công!');
                }
            }
        } else {
            $params['password'] = Hash::make($params['password']);
            $res = $this->admin->create($params);
            if($res){
                $res->roles()->sync($roles);
                return back()->with('success_message', 'Tạo thành viên thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function changePass(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý tài khoản!');
        }
        $params = $request->only('id', 'password');
        if(isset($params['id'])){
            $params['password'] = Hash::make($params['password']);
            $admin = $this->admin->first(['id' => $params['id']]);
            if($admin){
                $res = $this->admin->update($admin, $params);
                if($res){
                    return back()->with('success_message', 'Cập nhật mật khẩu thành công!');
                }
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
        $resR = $this->admin->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }

    public function report(Request $request)
    {
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return response(['success' => 0, 'message' => 'Bạn không có quyền quản lý tài khoản']);
        }
        $id = $request->get('id', '');
        $project_id = $request->get('project_id', 0);
        $account = $this->admin->first(['id' => $id]);
        if (empty($account)) {
            return response(['success' => 0, 'message' => 'Không tìm thấy tài khoản!']);
        }
        $start_time =  strtotime($request->get('start_time',''));
        $end_time =  strtotime($request->get('end_time',''));
        $end_time = $end_time ? strtotime('tomorrow', $end_time) - 1 : false;

        $tickets = $this->ticketRepo->getTicketByAdmin([$id], $project_id, $start_time, $end_time);
        $project = $this->projectRepo->get(['id' => $tickets->pluck('project_id')->all()])->load('admin')->map(function ($project) {
            $project->admin_ids = $project->admin->pluck('id')->all();
            return $project;
        })->keyBy('id')->all();

        $data = $tickets->groupBy('project_id')->map(function ($tickets, $project_id) use ($project, $id){
            $data['project'] = @$project[$project_id]['name'];
            $data['report']['total'] = 0;
            $data['report']['new'] = 0;
            $data['report']['expired'] = 0;
            $data['report']['done'] = 0;
            $data['report']['done_on_time'] = 0;
            $data['report']['done_out_time'] = 0;
            $data['report']['percent'] = 0;
            $data['report']['qty'] = 0;
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
                    $phaseGroupId = $ticket['group_id'] . '_' . $ticket['phase_id'];
                    if (!in_array($phaseGroupId, $phaseGroupIds) && in_array($id, $project[$project_id]['admin_ids'] ?? [])) {
                        $phaseGroup = ($ticket->group->phaseGroup ?? collect([]))->where('group_id', $ticket['group_id'])->where('phase_id', $ticket['phase_id'])->first();
                        $data['report']['qty'] += $phaseGroup->qty ?? 0;
                        $phaseGroupIds[] = $phaseGroupId;
                    }
                }
            }
            $data['report']['percent'] = !empty($data['report']['qty']) ? round($data['report']['done'] / $data['report']['qty'] * 100) : 0;
            return $data;
        })->values()->all();
        return response(['success' => 1, 'data' => $data, 'project' => array_values($project)]);
    }
}

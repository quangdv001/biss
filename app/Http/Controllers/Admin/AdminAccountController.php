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
        if(!$user->hasRole('super_admin') && !$user->hasRole('account')){
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
        if(!$user->hasRole('super_admin') && !$user->hasRole('account')){
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
        if(!$user->hasRole('super_admin') && !$user->hasRole('account')){
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
        if(!$user->hasRole('super_admin') && !$user->hasRole('account')){
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
        if(!$user->hasRole('super_admin') && !$user->hasRole('account')){
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
        $projectByAdmin = $this->projectRepo->getProjectByAdmin($id)->keyBy('id');
        $data = $this->ticketRepo->getTicketByAdmin($id, $project_id, $start_time, $end_time)->groupBy('project_id')->map(function ($tickets, $project_id) use ($projectByAdmin){
            $data['project'] = @$projectByAdmin[$project_id]['name'];
            $data['report']['total'] = count($tickets);
            $data['report']['new'] = 0;
            $data['report']['expired'] = 0;
            $data['report']['done'] = 0;
            $data['report']['done_on_time'] = 0;
            $data['report']['done_out_time'] = 0;
            $data['report']['percent'] = 0;
            if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    if ($ticket['status'] == 0 && empty($ticket['complete_time'])) {
                        if (time() > $ticket['deadline_time']) {
                            $data['report']['expired'] += 1;
                        } else {
                            $data['report']['new'] += 1;
                        }
                    }
                    if ($ticket['status'] == 1 || !empty($ticket['complete_time'])) {
                        $data['report']['done'] += 1;
                        if ($ticket['complete_time'] <= $ticket['deadline_time']) {
                            $data['report']['done_on_time'] += 1;
                        } else {
                            $data['report']['done_out_time'] += 1;
                        }
                    }
                }
            }
            $data['report']['percent'] = !empty($data['report']['total']) ? round($data['report']['done'] / $data['report']['total'] * 100) : 0;
            return $data;
        })->values()->all();
        return response(['success' => 1, 'data' => $data, 'project' => array_values($projectByAdmin->all())]);
    }
}

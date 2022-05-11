<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\NotyRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $ticketRepo;
    private $adminRepo;
    private $phase;
    private $noty;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo,TicketRepo $ticketRepo, AdminRepo $adminRepo, PhaseRepo $phase, NotyRepo $noty)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->ticketRepo = $ticketRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
        $this->noty = $noty;
    }

    public function index(Request $request, $gid, $pid = 0){
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $group = $this->groupRepo->first(['id' => $gid]);
        if(empty($group)){
            return back()->with('success_message', 'Không tìm thấy nhóm!');
        }
        $id = $group->project_id;
        $project = $this->projectRepo->first(['id' => $id], [], ['group','admin']);
        if(empty($project)){
            return back()->with('success_message', 'Không tìm thấy dự án!');
        }
        if (!$isAdmin && !in_array($user->id, $project->admin->pluck('id')->all())) {
            return back()->with('error_message', 'Bạn không có quyền vào nhóm!');
        }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'])->keyBy('id');
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $admins = $project->admin ?? [];

        $params['project_id'] = $id;
        $params['group_id'] = $gid;
        $params['phase_id'] = $pid;
        $data = $this->ticketRepo->get($params, [], ['admin','creator']);
        return view('admin.ticket.index2', compact('data', 'project', 'admins', 'phase', 'pid', 'gid', 'group', 'isAdmin'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        $params = $request->only('id', 'name', 'description', 'input', 'output', 'status', 'qty', 'priority', 'deadline_time', 'project_id', 'group_id', 'phase_id');
        $params['deadline_time'] = !empty($params['deadline_time']) ? strtotime($params['deadline_time']) : null;
        $params['status'] = isset($params['status']) ? 1 : 0;
        $params['qty'] = !empty($params['qty']) ? (int)$params['qty'] : 1;
        $params['priority'] = !empty($params['priority']) ? (int)$params['priority'] : 2;
        $admins = $request->get('admin',[]);
        if(isset($params['id'])){
            $ticket = $this->ticketRepo->first(['id' => $params['id']]);
            if($ticket){
                $ticket->load('admin');
                $isAdmin = $user->hasRole(['super_admin', 'account']);
                if (!$isAdmin && !in_array($user->id, $ticket->admin->pluck('id')->all()) && $user->id != @$ticket->admin_id_c) {
                    return back()->with('error_message', 'Bạn không có quyền sửa ticket!');
                }
                if ($params['status'] == 1) {
                    $params['complete_time'] = $ticket->complete_time ?? time();
                } else {
                    $params['complete_time'] = null;
                }
                $res = $this->ticketRepo->update($ticket, $params);
                if($res){
                    $res->admin()->sync($admins);
                    return back()->with('success_message', 'Cập nhật ticket thành công!');
                }
            }
        } else {
            if ($params['status'] == 1) {
                $params['complete_time'] = time();
            } else {
                $params['complete_time'] = null;
            }
            $params['created_time'] = time();
            $params['admin_id_c'] = $user->id;
            $res = $this->ticketRepo->create($params);
            if($res){
                $res->admin()->sync($admins);
                $this->createNoty($admins, $res);
                return back()->with('success_message', 'Tạo ticket thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $user = auth('admin')->user();
        $id = $request->input('id');
        $ticket = $this->ticketRepo->first(['id' => $id]);
        if(empty($ticket)){
            return response()->json(['success' => 0]);
        }
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        if (!$isAdmin && $user->id != @$ticket->admin_id_c) {
            return back()->with('error_message', 'Bạn không có quyền sửa ticket!');
        }
        $resR = $this->ticketRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }

    private function createNoty($admins, $data){
        if(!empty($admins)){
            foreach($admins as $v){
                $check = $this->noty->first(['admin_id' => $v, 'group_id' => $data->group_id, 'type' => 1]);
                $params = [];
                $params['status'] = 1;
                $params['admin_id_c'] = $data->admin_id_c;
                if($check){
                    $this->noty->update($check, $params);
                } else {
                    $params['admin_id'] = $v;
                    $params['project_id'] = $data->project_id;
                    $params['group_id'] = $data->group_id;
                    $params['phase_id'] = $data->phase_id;
                    $params['type'] = 1;
                    $this->noty->create($params);
                }
            }
        }
    }
}

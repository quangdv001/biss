<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
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

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo,TicketRepo $ticketRepo, AdminRepo $adminRepo, PhaseRepo $phase)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->ticketRepo = $ticketRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
    }

    public function index(Request $request, $gid, $pid = 0){
        $group = $this->groupRepo->first(['id' => $gid]);
        $id = $group->project_id;
        $project = $this->projectRepo->first(['id' => $id], [], ['group.admin','admin']);
        if(empty($project)){
            return back()->with('success_message', 'Không tìm thấy dự án!');
        }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'])->keyBy('id');
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $admins = $project->admin ?? [];

        $params['project_id'] = $id;
        $params['group_id'] = $gid;
        $params['phase_id'] = $pid;
        $data = $this->ticketRepo->get($params, [], ['admin']);
        return view('admin.ticket.index2', compact('data', 'project', 'admins', 'phase', 'pid', 'gid', 'group'));
    }

    public function create(Request $request){
        $admin = auth('admin')->user();
        $params = $request->only('id', 'name', 'description', 'input', 'output', 'status', 'deadline_time', 'complete_time', 'project_id', 'group_id', 'phase_id');
        $params['deadline_time'] = $params['deadline_time'] ? strtotime($params['deadline_time']) : null;
        $params['complete_time'] = $params['complete_time'] ? strtotime($params['complete_time']) : null;
        $params['status'] = isset($params['status']) ? 1 : 0;
        if(isset($params['id'])){
            $ticket = $this->ticketRepo->first(['id' => $params['id']]);
            if($ticket){
                $res = $this->ticketRepo->update($ticket, $params);
                if($res){
                    $res->admin()->sync($request->get('admin',[]));
                    return back()->with('success_message', 'Cập nhật ticket thành công!');
                }
            }
        } else {
            $params['created_time'] = time();
            $params['admin_id_c'] = $admin->id;
            $res = $this->ticketRepo->create($params);
            if($res){
                $res->admin()->sync($request->get('admin',[]));
                return back()->with('success_message', 'Tạo ticket thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $id = $request->input('id');
        $resR = $this->ticketRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

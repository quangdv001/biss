<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $adminRepo;
    private $phase;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo, AdminRepo $adminRepo, PhaseRepo $phase)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
    }

    public function index(Request $request, $id, $pid = 0){
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $gid = 0;
        $project = $this->projectRepo->first(['id' => $id], [], ['group.admin', 'admin', 'ticket.admin']);
        if(empty($project)){
            return back()->with('error_message', 'Không tìm thấy dự án!');
        }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'])->keyBy('id');
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $admins = $project->admin ?? [];
        if (!$isAdmin && !in_array($user->id, $project->admin->pluck('id')->all())) {
            return back()->with('error_message', 'Bạn không có quyền vào dự án!');
        }
        $groupByProject = $project->group->keyBy('id');
        $reportMember = $project->admin->map(function ($member){
            $data = $member->toArray();
            $data['report']['total'] = 0;
            $data['report']['new'] = 0;
            $data['report']['expired'] = 0;
            $data['report']['done'] = 0;
            $data['report']['done_on_time'] = 0;
            $data['report']['done_out_time'] = 0;
            $data['report']['percent'] = 0;
            return $data;
        })->keyBy('id')->all();
        $reportGroup = $project->ticket->where('phase_id', $pid)->groupBy('group_id')->map(function ($tickets, $group_id) use ($groupByProject, &$reportMember) {
            $data['group'] = @$groupByProject[$group_id]['name'];
            $data['report']['total'] = count($tickets);
            $data['report']['new'] = 0;
            $data['report']['expired'] = 0;
            $data['report']['done'] = 0;
            $data['report']['done_on_time'] = 0;
            $data['report']['done_out_time'] = 0;
            $data['report']['percent'] = 0;
            if (!empty($tickets)) {
                foreach ($tickets as $ticket) {
                    if ($ticket['status'] == 0) {
                        if (time() > $ticket['deadline_time']) {
                            $data['report']['expired'] += 1;
                        } else {
                            $data['report']['new'] += 1;
                        }
                    }
                    if ($ticket['status'] == 1) {
                        $data['report']['done'] += 1;
                        if ($ticket['complete_time'] <= $ticket['deadline_time']) {
                            $data['report']['done_on_time'] += 1;
                        } else {
                            $data['report']['done_out_time'] += 1;
                        }
                    }
                    if (!empty($ticket->admin)) {
                        foreach ($ticket->admin as $member) {
                            $reportMember[$member->id]['report']['total'] += count($tickets);
                            if ($ticket['status'] == 0) {
                                if (time() > $ticket['deadline_time']) {
                                    $reportMember[$member->id]['report']['expired'] += 1;
                                } else {
                                    $reportMember[$member->id]['report']['new'] += 1;
                                }
                            }
                            if ($ticket['status'] == 1) {
                                $reportMember[$member->id]['report']['done'] += 1;
                                if ($ticket['complete_time'] <= $ticket['deadline_time']) {
                                    $reportMember[$member->id]['report']['done_on_time'] += 1;
                                } else {
                                    $reportMember[$member->id]['report']['done_out_time'] += 1;
                                }
                            }
                        }
                    }
                }
            }
            $data['report']['percent'] = !empty($data['report']['total']) ? round($data['report']['done'] / $data['report']['total'] * 100) : 0;
            return $data;
        })->values()->all();
        $reportMember = array_values($reportMember);

        return view('admin.group.index2', compact('project', 'admins', 'phase', 'pid', 'id', 'gid', 'reportGroup', 'reportMember', 'isAdmin'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý nhóm!');
        }
        $params = $request->only('id', 'project_id', 'name');
        if(isset($params['id'])){
            $group = $this->groupRepo->first(['id' => $params['id']]);
            if($group){
                $res = $this->groupRepo->update($group, $params);
                if($res){
                    return back()->with('success_message', 'Cập nhật nhóm thành công!');
                }
            }
        } else {
            $res = $this->groupRepo->create($params);
            if($res){
                return back()->with('success_message', 'Tạo nhóm thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function createPhase(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý phase!');
        }
        $params = $request->only('project_id', 'start_time', 'end_time', 'name');
        $params['start_time'] = $params['start_time'] ? strtotime($params['start_time']) : null;
        $params['end_time'] = $params['end_time'] ? strtotime($params['end_time']) + 86399 : null;
        $res = $this->phase->create($params);
        if($res){
            $project = $this->projectRepo->first(['id' => $res->project_id]);
            if($project){
                $paramsP['accept_time'] = $res->start_time;
                $paramsP['expired_time'] = $res->end_time;
                $this->projectRepo->update($project, $paramsP);
            }
            return back()->with('success_message', 'Tạo phase thành công!');
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return response(['success' => 0]);
        }
        $id = $request->input('id');
        $resR = $this->groupRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $adminRepo;
    private $phase;
    private $role;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo, AdminRepo $adminRepo, PhaseRepo $phase, RoleRepo $role)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
        $this->role = $role;
    }

    public function index(Request $request, $id, $pid = 0)
    {
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $isGuest = $user->hasRole(['guest']);
        $gid = 0;
        $project = $this->projectRepo->first(['id' => $id], [], ['group', 'admin', 'ticket.admin']);
        if (empty($project)) {
            return back()->with('error_message', 'Không tìm thấy dự án!');
        }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'], ['phaseGroup', 'group'])->keyBy('id');
        
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $phaseGroup = $phase[$pid]->phaseGroup->keyBy('group_id');
        $project->group->load(['phaseGroup' => function ($query) use ($pid) {
            $query->where('phase_id', $pid);
        }])->map(function ($gr) {
            $gr->phase_qty = $gr->phaseGroup->sum('qty');
        })->keyBy('id');
        $admins = $project->admin ?? [];
        // if (!$isAdmin && !in_array($user->id, $project->admin->pluck('id')->all())) {
        //     return back()->with('error_message', 'Bạn không có quyền vào dự án!');
        // }
        $reportMember = $project->admin->map(function ($member) {
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
        $group = $project->group->keyBy('id');
        $reportGroup = $project->ticket->where('phase_id', $pid)->groupBy('group_id')->map(function ($tickets, $group_id) use ($group, &$reportMember, $phaseGroup) {
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
                    if (!empty($ticket->admin)) {
                        foreach ($ticket->admin as $member) {
                            if(isset($reportMember[$member->id])){

                                $reportMember[$member->id]['report']['total'] += $qty;
                                if ($ticket['status'] == 0) {
                                    if (time() > $ticket['deadline_time']) {
                                        $reportMember[$member->id]['report']['expired'] += $qty;
                                    } else {
                                        $reportMember[$member->id]['report']['new'] += $qty;
                                    }
                                }
                                if ($ticket['status'] == 1) {
                                    $reportMember[$member->id]['report']['done'] += $qty;
                                    if ($ticket['complete_time'] <= $ticket['deadline_time']) {
                                        $reportMember[$member->id]['report']['done_on_time'] += $qty;
                                    } else {
                                        $reportMember[$member->id]['report']['done_out_time'] += $qty;
                                    }
                                }
                                $reportMember[$member->id]['report']['percent'] = !empty($reportMember[$member->id]['report']['total']) ? round($reportMember[$member->id]['report']['done'] / $reportMember[$member->id]['report']['total'] * 100) : 0;
                            }
                        }
                    }
                }
            }
            $data['report']['percent'] = !empty($phaseGroup[$group_id]['qty']) ? round($data['report']['done'] / $phaseGroup[$group_id]['qty'] * 100) : 0;
            return $data;
        })->all();
        $reportMember = array_values($reportMember);
        $role = $this->role->getRole();
        return view('admin.group.index2', compact('project', 'admins', 'phase', 'pid', 'id', 'gid', 'reportGroup', 'reportMember', 'isAdmin', 'isGuest', 'role', 'phaseGroup'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý nhóm!');
        }
        $params = $request->only('id', 'project_id', 'role_id', 'name');
        $qty = $request->get('qty', []);
        if(isset($params['id'])){
            $group = $this->groupRepo->first(['id' => $params['id']]);
            if($group){
                $res = $this->groupRepo->update($group, $params);
                if($res){
                    if(!empty($qty)){
                        
                        $paramsQ = [];
                        foreach($qty as $k => $v){
                            $paramsQ[$k]['qty'] = $v;
                        }
                        $res->phase()->sync($paramsQ);
                    }
                    // $this->groupRepo->cuPhaseGroup($res->id, $qty);
                    return back()->with('success_message', 'Cập nhật nhóm thành công!');
                }
            }
        } else {
            $res = $this->groupRepo->create($params);
            if($res){
                $this->groupRepo->cuPhaseGroup($res->id, $qty);
                return back()->with('success_message', 'Tạo nhóm thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function createPhase(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý phase!');
        }
        $params = $request->only('id', 'project_id', 'start_time', 'end_time', 'name');
        $params['start_time'] = $params['start_time'] ? strtotime($params['start_time']) : null;
        $params['end_time'] = $params['end_time'] ? strtotime($params['end_time']) + 86399 : null;
        if (isset($params['id'])) {
            $phases = $this->phase->get(['project_id' => $params['project_id']])->keyBy('id');
            $max = $phases->max('id');
            $res = $this->phase->update($phases[$params['id']], $params);
            if($res){
                $project = $this->projectRepo->first(['id' => $res->project_id]);
                if($project && $max == $res->id){
                    $paramsP['accept_time'] = $res->start_time;
                    $paramsP['expired_time'] = $res->end_time;
                    $this->projectRepo->update($project, $paramsP);
                }
                return redirect()->route('admin.group.index', ['id' => $res->project_id, 'pid' => $res->id])->with('success_message', 'Sửa phase thành công!');
            }
        } else {

            $res = $this->phase->create($params);
            if($res){
                $project = $this->projectRepo->first(['id' => $res->project_id]);
                if($project){
                    $paramsP['accept_time'] = $res->start_time;
                    $paramsP['expired_time'] = $res->end_time;
                    $this->projectRepo->update($project, $paramsP);
                }
                return redirect()->route('admin.group.index', ['id' => $res->project_id])->with('success_message', 'Tạo phase thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function removePhase(Request $request) {
        $params = $request->only('project_id', 'id');
        $phases = $this->phase->get(['project_id' => $params['project_id']]);
        $res['success'] = 0;
        if ($phases->count() > 1) {
            $this->phase->remove($params['id']);
            $phase = $this->phase->first(['project_id' => $params['project_id']], ['id' => 'DESC']);
            $project = $this->projectRepo->first(['id' => $params['project_id']]);
            if($project){
                $paramsP['accept_time'] = $phase->start_time;
                $paramsP['expired_time'] = $phase->end_time;
                $this->projectRepo->update($project, $paramsP);
            }
            $res['success'] = 1;
        }
        return response()->json($res);
    }

    public function remove(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin'])){
            return response(['success' => 0]);
        }
        $id = $request->input('id');
        $pid = $request->input('pid');
        $resR = $this->groupRepo->deletePhaseGroup($id, $pid);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

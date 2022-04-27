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
        $gid = 0;
        $project = $this->projectRepo->first(['id' => $id], [], ['group.admin','admin']);
        if(empty($project)){
            return back()->with('success_message', 'Không tìm thấy dự án!');
        }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'])->keyBy('id');
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $admins = $project->admin ?? [];
        return view('admin.group.index2', compact('project', 'admins', 'phase', 'pid', 'id', 'gid'));
    }

    public function create(Request $request){
        $params = $request->only('id', 'project_id', 'name');
        if(isset($params['id'])){
            $group = $this->groupRepo->first(['id' => $params['id']]);
            if($group){
                $res = $this->groupRepo->update($group, $params);
                if($res){
                    $res->admin()->sync($request->get('admin_group',[]));
                    return back()->with('success_message', 'Cập nhật nhóm thành công!');
                }
            }
        } else {
            $res = $this->groupRepo->create($params);
            if($res){
                $res->admin()->sync($request->get('admin_group',[]));
                return back()->with('success_message', 'Tạo nhóm thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function createPhase(Request $request){
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
        $id = $request->input('id');
        $resR = $this->groupRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

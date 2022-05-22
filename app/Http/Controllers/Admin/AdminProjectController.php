<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    private $projectRepo;
    private $adminRepo;
    private $phase;

    public function __construct(ProjectRepo $projectRepo, AdminRepo $adminRepo, PhaseRepo $phase)
    {
        $this->projectRepo = $projectRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
    }

    public function index(Request $request){
        $request->flash();
        $limit = $request->get('limit', 20);
        $name = $request->get('name', '');
        $field = $request->get('field', '');
        $condition['status'] = $request->get('status', 1);
        if(!empty($name)){
            $condition['name'] = $name;
        }
        if(!empty($field)){
            $condition['field'] = $field;
        }
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin','account']);
        if($user->hasRole(['super_admin'])){
            $data = $this->projectRepo->paginate($condition, $limit, ['status' => 'ASC', 'id' => 'DESC'], ['planer', 'executive', 'admin']);
        } else {
            $data = $this->projectRepo->search($condition, $limit, $user->id);
        }
        $data->load('ticket')->map(function ($project){
            $project->has_late = false;
            if(!empty($project->ticket)){
                foreach ($project->ticket as $ticket){
                    if($ticket->status == 0 && $ticket->deadline_time < time()){
                        $project->has_late = true;
                    }
                }
            }
            return $project;
        });
        $admins = $this->adminRepo->get();
        return view('admin.project.index', compact('data', 'admins', 'isAdmin'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý dự án!');
        }
        $params = $request->only( 'id','name', 'description', 'note', 'planer_id', 'executive_id', 'package', 'payment_month', 'fanpage', 'website', 'extra_link', 'accept_time', 'expired_time', 'created_time', 'status', 'field');
        $params['status'] = isset($params['status']) ? 1 : 2;
        $params['accept_time'] = $params['accept_time'] ? strtotime($params['accept_time']) : null;
        $params['expired_time'] = $params['expired_time'] ? strtotime($params['expired_time']) : null;
        if(isset($params['id'])){
            $project = $this->projectRepo->first(['id' => $params['id']]);
            if($project){
                $res = $this->projectRepo->update($project, $params);
                if($res){
                    $phase = $this->phase->first(['project_id' => $res->id], ['id' => 'DESC']);
                    if($phase){
                        $paramsP['start_time'] = $res->accept_time;
                        $paramsP['end_time'] = $res->expired_time + 86399;
                        $this->phase->update($phase, $paramsP);
                    }
                    $res->admin()->sync($request->get('admin_project',[]));
                    return back()->with('success_message', 'Cập nhật dự án thành công!');
                }
            }
        } else {
            $params['created_time'] = time();
            $res = $this->projectRepo->create($params);
            if($res){
                if($res->accept_time > 0 && $res->expired_time > 0){
                    $paramsP['name'] = 'Phase 1';
                    $paramsP['start_time'] = $res->accept_time;
                    $paramsP['end_time'] = $res->expired_time + 86399;
                    $paramsP['project_id'] = $res->id;
                    $this->phase->create($paramsP);
                }
                $res->admin()->sync($request->get('admin_project',[]));
                return back()->with('success_message', 'Tạo dự án thành công!');
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
        $resR = $this->projectRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }


}

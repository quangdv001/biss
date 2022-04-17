<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\ProjectRepo;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    private $projectRepo;
    private $adminRepo;

    public function __construct(ProjectRepo $projectRepo, AdminRepo $adminRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->adminRepo = $adminRepo;
    }

    public function index(Request $request){
        $limit = $request->get('limit', 10);
        $name = $request->get('name', '');
        $condition = [];
        if(!empty($name)){
            $condition['name'] = $name;
        }
        $data = $this->projectRepo->paginate($condition, $limit);
        $data->load('planer', 'executive', 'admin');
        $admins = $this->adminRepo->get();
        return view('admin.project.index', compact('data', 'admins'));
    }

    public function create(Request $request){
        $params = $request->only( 'id','name', 'description', 'note', 'planer_id', 'executive_id', 'package', 'payment_month', 'fanpage', 'website', 'accept_time', 'expired_time', 'created_time', 'status');
        $params['accept_time'] = $params['accept_time'] ? strtotime($params['accept_time']) : null;
        $params['expired_time'] = $params['expired_time'] ? strtotime($params['expired_time']) : null;
        if(isset($params['id'])){
            $project = $this->projectRepo->first(['id' => $params['id']]);
            if($project){
                $res = $this->projectRepo->update($project, $params);
                if($res){
                    $res->admin()->sync($request->get('admin_project',[]));
                    return back()->with('success_message', 'Cập nhật dự án thành công!');
                }
            }
        } else {
            $params['created_time'] = time();
            $res = $this->projectRepo->create($params);
            if($res){
                $res->admin()->sync($request->get('admin_project',[]));
                return back()->with('success_message', 'Tạo dự án thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $id = $request->input('id');
        $resR = $this->projectRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

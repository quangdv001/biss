<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\ProjectRepo;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $adminRepo;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo, AdminRepo $adminRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->adminRepo = $adminRepo;
    }

    public function index(Request $request){
        $req = $request->only('id');
        $data = $this->projectRepo->first($req);
        if(empty($data)){
            return back()->with('success_message', 'Không tìm thấy dự án!');
        }
        $data->load('group.admin','admin');
        $admins = $data->admin ?? [];
        return view('admin.group.index', compact('data', 'admins'));
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

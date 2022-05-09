<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\RoleRepo;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    private $role;
    public function __construct(RoleRepo $role)
    {
        $this->role = $role;
    }

    public function index(){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý chức vụ!');
        }
        $data = $this->role->get();
        return view('admin.role.index', compact('data'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return back()->with('error_message', 'Bạn không có quyền quản lý chức vụ!');
        }
        $params = $request->only('id', 'name', 'slug');
        if(isset($params['id'])){
            $role = $this->role->first(['id' => $params['id']]);
            if($role){
                $res = $this->role->update($role, $params);
                if($res){
                    return back()->with('success_message', 'Cập nhật chức vụ thành công!');
                }
            }
        } else {
            $res = $this->role->create($params);
            if($res){
                return back()->with('success_message', 'Tạo chức vụ thành công!');
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
        $resR = $this->role->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
    
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\RoleRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    private $admin, $role;
    public function __construct(AdminRepo $admin, RoleRepo $role)
    {
        $this->admin = $admin;
        $this->role = $role;
    }

    public function index(){
        $data = $this->admin->get([],['id' => 'DESC'], ['roles']);
        $roles = $this->role->get();
        return view('admin.account.index', compact('data', 'roles'));
    }

    public function create(Request $request){
        $params = $request->only('id', 'username', 'password', 'status', 'name', 'phone', 'address', 'email', 'mns', 'birthday');
        $params['birthday'] = $params['birthday'] ? strtotime($params['birthday']) : null;
        $params['status'] = isset($params['status']) ? 1 : 0;
        $roles = $request->input('roles', []);
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

    public function remove(Request $request){
        $id = $request->input('id');
        $resR = $this->admin->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

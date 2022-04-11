<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    private $admin;
    public function __construct(AdminRepo $admin)
    {
        $this->admin = $admin;   
    }

    public function index(){
        return view('admin.profile.index');
    }

    public function postIndex(Request $request){
        $params = $request->only('name', 'address', 'phone', 'email');
        $admin = auth('admin')->user();
        $resU = $this->admin->update($admin, $params);
        if($resU){
            return back()->with('success_message', 'Cập nhật thành công!');
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function password(){
        return view('admin.profile.password');
    }

    public function postPassword(Request $request){
        $params = $request->only('old_password','password','confirm_password');
        
        $admin = auth('admin')->user();
        if(!Hash::check(trim($params['old_password']), $admin->password)){
            return back()->with([
                'error_message' => 'Mật khẩu cũ không chính xác!',
            ]);
        }
        
        if(trim($params['password'] !== trim($params['confirm_password']))){
            return back()->with([
                'error_message' => 'Mật khẩu xác nhận không chính xác!',
            ]);
        }

        $paramsU['password'] = Hash::make(trim($params['password']));
        $resU = $this->admin->update($admin, $paramsU);
        if($resU){
            return back()->with('success_message', 'Cập nhật thành công!');
        }

        return back()->with([
            'error_message' => 'Mời nhập đủ thông tin!',
        ]);
    }
}

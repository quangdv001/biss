<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function login(){
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {
        $remember = $request->filled('remember');
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        $credentials['status'] = 1;
        if (auth('admin')->attempt($credentials, $remember)) {
            return redirect()->intended('admin');
        }
 
        return back()->withErrors([
            'error' => 'Thông tin đăng nhập chưa chính xác!',
        ]);
    }

    public function logout(){
        auth('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}

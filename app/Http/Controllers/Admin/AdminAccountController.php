<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use Illuminate\Http\Request;

class AdminAccountController extends Controller
{
    private $admin;
    public function __construct(AdminRepo $admin)
    {
        $this->admin = $admin;
    }

    public function index(){
        $data = $this->admin->get();
        return view('admin.account.index', compact('data'));
    }
}

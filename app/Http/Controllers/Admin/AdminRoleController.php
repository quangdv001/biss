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
        $data = $this->role->get();
        return view('admin.role.index', compact('data'));
    }
    
}

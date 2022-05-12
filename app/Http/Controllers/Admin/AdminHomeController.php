<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\NotyRepo;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    private $noty;
    public function __construct(NotyRepo $noty)
    {
        $this->noty = $noty;
    }

    public function index(){
        return view('admin.home.index');
    }

    public function getNoty(){
        $user = auth('admin')->user();
        $data = $this->noty->get(['admin_id' => $user->id, 'status' => 1], ['updated_at' => 'DESC'], ['project', 'group', 'admin', 'adminc']);

        $res['success'] = 1;
        $res['html'] = view('admin.home.noty', compact('data'))->render();
        return response()->json($res);
    }

    public function detailNoty(Request $request){
        $id = $request->input('id', 0);
        $noty = $this->noty->first(['id' => $id]);
        $res['success'] = 0;
        if($noty){
            $params['status'] = 0;
            $this->noty->update($noty, $params);

            $res['success'] = 1;
            $res['url'] = route('admin.ticket.index', ['gid' => $noty->group_id, 'pid' => $noty->phase_id]);
        }

        return response()->json($res);
    }
}

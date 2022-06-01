<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\GroupRepo;
use App\Repo\NotyRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    private $noty, $ticket, $group;
    public function __construct(NotyRepo $noty, TicketRepo $ticket, GroupRepo $group)
    {
        $this->noty = $noty;
        $this->ticket = $ticket;
        $this->group = $group;
    }

    public function index(){
        return view('admin.home.index');
    }

    public function getNoty(){
        $user = auth('admin')->user();
        
        $tickets = $this->ticket->getTicketByAdmin([$user->id], '', '', '');
        $time = time();
        $ticket = $tickets->where('status', 0);
        $group = $this->group->get(['id' => $ticket->pluck('group_id')->toArray()], [], ['project'])->keyBy('id');
        $new = $ticket->where('status', 0)->where('deadline_time', '>', $time)->pluck('group_id')->countBy();
        $old = $ticket->where('status', 0)->where('deadline_time', '<=', $time)->pluck('group_id')->countBy();
        $data = $this->noty->get(['admin_id' => $user->id, 'status' => 1], ['updated_at' => 'DESC'], ['project', 'group', 'admin', 'adminc']);

        $res['success'] = 1;
        $res['html'] = view('admin.home.noty', compact('ticket', 'group', 'new', 'old', 'data'))->render();
        $res['count'] = ($data->count() + $ticket->count());
        return response()->json($res);
    }

    public function detailNoty(Request $request){
        $id = $request->input('id', 0);
        $res['success'] = 1;
        $res['url'] = route('admin.ticket.index', ['gid' => $id]);

        return response()->json($res);
    }

    public function viewNoty(Request $request){
        $id = $request->input('id', 0);
        $noty = $this->noty->first(['id' => $id]);
        $res['success'] = 0;
        if($noty){
            $params['status'] = 0;
            $this->noty->update($noty, $params);

            $res['success'] = 1;
            $res['url'] = route('admin.ticket.index', ['gid' => $noty->group_id, 'pid' => $noty->phase_id]) . ($noty->type == 1 ? '#modalNote' : '');
        }

        return response()->json($res);
    }
}

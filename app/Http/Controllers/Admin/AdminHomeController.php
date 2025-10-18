<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\GroupRepo;
use App\Repo\NotyRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    private $noty, $ticket, $group, $project, $role;
    public function __construct(NotyRepo $noty, TicketRepo $ticket, GroupRepo $group, ProjectRepo $project, RoleRepo $role)
    {
        $this->noty = $noty;
        $this->ticket = $ticket;
        $this->group = $group;
        $this->project = $project;
        $this->role = $role;
    }

    public function intro(){
        return view('admin.home.intro');
    }

    public function dashboard(Request $request){
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);

        // Lấy danh sách roles và projects
        $roles = $this->role->get();
        $projects = $this->project->get([], [], ['admin']);

        // Lấy thông tin user hiện tại
        $userRoles = $user->roles;

        return view('admin.home.dashboard', compact('user', 'isAdmin', 'roles', 'projects', 'userRoles'));
    }

    public function getPersonalReport(Request $request){
        $user = auth('admin')->user();

        $startTime = $request->input('start_time') ? strtotime($request->input('start_time')) : strtotime('-30 days');
        $endTime = $request->input('end_time') ? strtotime('tomorrow', strtotime($request->input('end_time'))) - 1 : time();
        $status = $request->input('status');

        // Lấy tất cả tickets của user
        $tickets = $this->ticket->getTicketByAdmin([$user->id], '', $startTime, $endTime);

        // Lọc theo trạng thái nếu có
        if ($status !== '' && $status !== null) {
            if ($status === 'expired') {
                // Trễ hạn: chưa hoàn thành và deadline < hiện tại
                $tickets = $tickets->where('status', 0)->where('deadline_time', '<', time());
            } else {
                $tickets = $tickets->where('status', $status);
            }
        }

        // Map dữ liệu
        $data = $tickets->map(function($ticket) {
            return [
                'id' => $ticket->id,
                'name' => $ticket->name,
                'description' => $ticket->description,
                'project_name' => $ticket->project->name ?? '',
                'group_name' => $ticket->group->name ?? '',
                'deadline_time' => $ticket->deadline_time,
                'complete_time' => $ticket->complete_time,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'qty' => $ticket->qty
            ];
        })->values();

        // Thống kê
        $stats = [
            'total' => $tickets->count(),
            'completed' => $tickets->where('status', 1)->count(),
            'pending' => $tickets->where('status', 0)->where('deadline_time', '>=', time())->count(),
            'expired' => $tickets->where('status', 0)->where('deadline_time', '<', time())->count(),
        ];

        return response()->json([
            'success' => 1,
            'data' => $data,
            'stats' => $stats
        ]);
    }

    public function getNoty(){
        $user = auth('admin')->user();

        $tickets = $this->ticket->getTicketByAdmin([$user->id], '', '', '');
        $time = time();
        $ticket = $tickets->where('status', 0);
        $group = $this->group->get(['id' => $ticket->pluck('group_id')->toArray()], [], ['project'])->keyBy('id');
        $new = $ticket->where('status', 0)->where('deadline_time', '>', $time);
        $old = $ticket->where('status', 0)->where('deadline_time', '<=', $time);
        $data = $this->noty->get(['admin_id' => $user->id, 'status' => 1], ['updated_at' => 'DESC'], ['project', 'group', 'admin', 'adminc']);
        // dd($new, $old);
        $res['success'] = 1;
        $res['html'] = view('admin.home.noty', compact('ticket', 'group', 'new', 'old', 'data'))->render();
        $res['count'] = ($data->count() + $ticket->count());
        return response()->json($res);
    }

    public function detailNoty(Request $request){
        $id = $request->input('id', 0);
        $ticket = $this->ticket->first(['id' => $id]);
        $res['success'] = 1;
        $res['url'] = route('admin.ticket.index', ['gid' => $ticket->group_id, 'pid' => $ticket->phase_id, 'id' => $id]);

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

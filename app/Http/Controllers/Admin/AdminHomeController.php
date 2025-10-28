<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\NotyRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    private $noty, $ticket, $group, $project, $role, $admin;
    public function __construct(NotyRepo $noty, TicketRepo $ticket, GroupRepo $group, ProjectRepo $project, RoleRepo $role, AdminRepo $admin)
    {
        $this->noty = $noty;
        $this->ticket = $ticket;
        $this->group = $group;
        $this->project = $project;
        $this->role = $role;
        $this->admin = $admin;
    }

    public function index(){
        $user = auth('admin')->user();

        // Nếu là admin thì vào dashboard, không thì vào intro
        if ($user->hasRole(['super_admin', 'account'])) {
            return redirect()->route('admin.home.dashboard');
        }

        return redirect()->route('admin.home.intro');
    }

    public function intro(){
        return view('admin.home.intro');
    }

    public function calendar(){
        $user = auth('admin')->user();

        // Lấy danh sách projects cho filter
        $projects = $this->project->get([], [], ['admin']);

        return view('admin.home.calendar', compact('user', 'projects'));
    }

    public function dashboard(Request $request){
        $user = auth('admin')->user();

        // Chỉ cho phép super_admin và account truy cập dashboard
        if (!$user->hasRole(['super_admin', 'account'])) {
            return redirect()->route('admin.home.intro')->with('error_message', 'Bạn không có quyền truy cập Dashboard');
        }

        $isAdmin = $user->hasRole(['super_admin', 'account']);

        // Lấy danh sách roles và projects
        $roles = $this->role->get();
        $projects = $this->project->get([], [], ['admin']);

        // Lấy danh sách admins để filter
        $admins = $this->admin->get();

        // Lấy thông tin user hiện tại
        $userRoles = $user->roles;

        return view('admin.home.dashboard', compact('user', 'isAdmin', 'roles', 'projects', 'userRoles', 'admins'));
    }

    public function getPersonalReport(Request $request){
        $user = auth('admin')->user();

        $startTime = $request->input('start_time') ? strtotime($request->input('start_time')) : strtotime('-30 days');
        $endTime = $request->input('end_time') ? strtotime('tomorrow', strtotime($request->input('end_time'))) - 1 : time();
        $status = $request->input('status');
        $adminId = $request->input('planer_id'); // Lọc theo nhân sự phụ trách

        // Nếu không chọn admin_id thì lấy của chính mình
        if (!$adminId) {
            $adminId = $user->id;
        }

        // Lấy tất cả tickets của nhân sự được chọn
        $tickets = $this->ticket->getTicketByAdmin([$adminId], '', $startTime, $endTime);

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
            // Lấy thông tin planer của dự án
            $planerName = 'Chưa có planer';
            if ($ticket->project && $ticket->project->planer) {
                $planerName = $ticket->project->planer->username;
            }

            return [
                'id' => $ticket->id,
                'name' => $ticket->name,
                'description' => $ticket->description,
                'project_name' => $ticket->project->name ?? '',
                'group_name' => $ticket->group->name ?? '',
                'deadline_time' => $ticket->deadline_time,
                'complete_time' => $ticket->complete_time,
                'status' => $ticket->status,
                'planer' => $planerName,
                'qty' => $ticket->qty
            ];
        })->values();

        // Thống kê
        $completedTickets = $tickets->where('status', 1);
        $completedOnTime = $completedTickets->filter(function($ticket) {
            return $ticket->complete_time && $ticket->complete_time <= $ticket->deadline_time;
        })->count();

        $totalCompleted = $completedTickets->count();
        $onTimeRate = $totalCompleted > 0 ? round(($completedOnTime / $totalCompleted) * 100, 1) : 0;

        $stats = [
            'total' => $tickets->count(),
            'completed' => $totalCompleted,
            'pending' => $tickets->where('status', 0)->where('deadline_time', '>=', time())->count(),
            'expired' => $tickets->where('status', 0)->where('deadline_time', '<', time())->count(),
            'completed_on_time' => $completedOnTime,
            'on_time_rate' => $onTimeRate,
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

    public function getProjectReport(Request $request){
        $user = auth('admin')->user();

        // Chỉ cho phép super_admin và account xem báo cáo dự án
        if (!$user->hasRole(['super_admin', 'account'])) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xem báo cáo này'], 403);
        }

        $planerId = $request->input('admin_id'); // Lọc theo planer

        $currentTime = time();
        $oneWeekLater = strtotime('+7 days');

        // Lấy tất cả dự án với quan hệ
        $projects = $this->project->get([], [], ['planer', 'ticket', 'group']);

        // Lọc theo planer nếu có
        if ($planerId) {
            $projects = $projects->where('planer_id', $planerId);
        }

        $activeProjects = [];
        $lateProjects = [];
        $pendingProjects = [];
        $expiringProjects = [];

        foreach ($projects as $project) {
            // Lấy thông tin planer
            $planerName = $project->planer ? $project->planer->username : 'Chưa có planer';

            $projectData = [
                'id' => $project->id,
                'name' => $project->name,
                'status' => $project->status,
                'expired_time' => $project->expired_time,
                'admins' => $planerName,
            ];

            // Đếm task
            $totalTasks = $project->ticket->count();
            $pendingTasks = $project->ticket->where('status', 0)->count();
            $completedTasks = $project->ticket->where('status', 1)->count();

            $projectData['total_tasks'] = $totalTasks;
            $projectData['pending_tasks'] = $pendingTasks;
            $projectData['completed_tasks'] = $completedTasks;

            // 1. Dự án đang hoạt động (status = 1)
            if ($project->status == 1) {
                $activeProjects[] = $projectData;
            }

            // 2. Dự án đã hoàn thành (status = 0) nhưng còn task chưa làm
            if ($project->status == 0 && $pendingTasks > 0) {
                // Lấy danh sách group có task chưa làm
                $pendingGroups = $project->ticket()
                    ->where('status', 0)
                    ->with('group')
                    ->get()
                    ->groupBy('group_id')
                    ->map(function($tickets, $groupId) {
                        $group = $tickets->first()->group;
                        return [
                            'group_name' => $group ? $group->name : 'N/A',
                            'pending_count' => $tickets->count()
                        ];
                    })
                    ->values();

                $projectData['pending_groups'] = $pendingGroups;
                $lateProjects[] = $projectData;
            }

            // 3. Dự án đang có task chưa hoàn thành
            if ($pendingTasks > 0) {
                $pendingProjects[] = $projectData;
            }

            // 4. Dự án sắp hết hạn (còn 7 ngày)
            if ($project->expired_time &&
                $project->expired_time > $currentTime &&
                $project->expired_time <= $oneWeekLater) {
                $daysLeft = ceil(($project->expired_time - $currentTime) / 86400);
                $projectData['days_left'] = $daysLeft;
                $expiringProjects[] = $projectData;
            }
        }

        return response()->json([
            'success' => 1,
            'data' => [
                'active' => $activeProjects,
                'late' => $lateProjects,
                'pending' => $pendingProjects,
                'expiring' => $expiringProjects,
            ]
        ]);
    }

    public function getCalendarData(Request $request){
        $user = auth('admin')->user();

        $projectId = $request->input('project_id');
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        // Convert dates to timestamps
        $startTime = $startDate ? strtotime($startDate) : strtotime('-30 days');
        $endTime = $endDate ? strtotime($endDate) : strtotime('+60 days');

        // Lấy tickets của user
        $ticketsQuery = $this->ticket->getTicketByAdmin([$user->id], '', $startTime, $endTime);

        // Lọc theo project nếu có
        if ($projectId) {
            $ticketsQuery = $ticketsQuery->where('project_id', $projectId);
        }

        // Map dữ liệu cho calendar
        $events = $ticketsQuery->map(function($ticket) {
            // Xác định màu sắc dựa trên trạng thái
            if ($ticket->status == 1) {
                $color = '#1BC5BD'; // Success - green
                $textColor = '#ffffff';
            } elseif ($ticket->deadline_time < time()) {
                $color = '#F64E60'; // Danger - red (trễ hạn)
                $textColor = '#ffffff';
            } else {
                $color = '#FFA800'; // Warning - yellow (chưa làm)
                $textColor = '#ffffff';
            }

            // Lấy thông tin người phụ trách
            $assignees = $ticket->admin->pluck('username')->toArray();
            $assigneesText = !empty($assignees) ? implode(', ', $assignees) : 'Chưa phân công';

            return [
                'id' => $ticket->id,
                'title' => $ticket->name,
                'start' => date('Y-m-d', $ticket->deadline_time),
                'end' => date('Y-m-d', $ticket->deadline_time),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => $textColor,
                'allDay' => true,
                'extendedProps' => [
                    'description' => $ticket->description,
                    'project_name' => $ticket->project->name ?? '',
                    'group_name' => $ticket->group->name ?? '',
                    'deadline_time' => $ticket->deadline_time,
                    'complete_time' => $ticket->complete_time,
                    'status' => $ticket->status,
                    'assignees' => $assigneesText,
                    'note' => $ticket->note,
                ]
            ];
        })->values();

        return response()->json($events);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\ProjectRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $ticketRepo;
    private $adminRepo;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo,TicketRepo $ticketRepo, AdminRepo $adminRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->ticketRepo = $ticketRepo;
        $this->adminRepo = $adminRepo;
    }

    public function index(Request $request){
        $params = $request->only('group_id','');
        $start_time = $request->start_time ? strtotime($request->start_time) : null;
        $end_time = $request->end_time ? strtotime($request->end_time) : null;
        if(!empty($start_time) && !empty($end_time)){
            $params['created_time'] = [$start_time, strtotime('tomorrow', $end_time)];
        }
        $name = $request->get('name', '');
        if(!empty($name)){
            $params['name'] = $name;
        }
        $data = $this->ticketRepo->paginate($params);
        $data->load('admin');
        return view('admin.ticket.index', compact('data'));
    }

    public function create(Request $request){
        $admin = auth('admin')->user();
        $params = $request->only('id', 'name', 'description', 'input', 'output', 'status', 'status', 'deadline_time', 'complete_time', 'project_id', 'group_id');
        if(isset($params['id'])){
            $ticket = $this->ticketRepo->first(['id' => $params['id']]);
            if($ticket){
                $res = $this->ticketRepo->update($ticket, $params);
                if($res){
                    $res->admin()->sync($request->get('admin_ticket',[]));
                    return back()->with('success_message', 'Cập nhật ticket thành công!');
                }
            }
        } else {
            $params['created_time'] = time();
            $params['admin_id_c'] = $admin->id;
            $res = $this->ticketRepo->create($params);
            if($res){
                $res->admin()->sync($request->get('admin_ticket',[]));
                return back()->with('success_message', 'Tạo ticket thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request){
        $id = $request->input('id');
        $resR = $this->ticketRepo->remove($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }
}

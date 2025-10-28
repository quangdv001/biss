<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhaseGroup;
use App\Repo\AdminRepo;
use App\Repo\GroupRepo;
use App\Repo\NoteRepo;
use App\Repo\NotyRepo;
use App\Repo\PhaseRepo;
use App\Repo\ProjectRepo;
use App\Repo\RoleRepo;
use App\Repo\TicketRepo;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    private $projectRepo;
    private $groupRepo;
    private $ticketRepo;
    private $adminRepo;
    private $phase;
    private $noty;
    private $note;
    private $role;

    public function __construct(ProjectRepo $projectRepo,GroupRepo $groupRepo,TicketRepo $ticketRepo, AdminRepo $adminRepo, PhaseRepo $phase, NotyRepo $noty, NoteRepo $note, RoleRepo $role)
    {
        $this->projectRepo = $projectRepo;
        $this->groupRepo = $groupRepo;
        $this->ticketRepo = $ticketRepo;
        $this->adminRepo = $adminRepo;
        $this->phase = $phase;
        $this->noty = $noty;
        $this->note = $note;
        $this->role = $role;
    }

    public function index(Request $request, $gid, $pid = 0){
        $user = auth('admin')->user();
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        $isSuperAdmin = $user->hasRole(['super_admin']);
        $group = $this->groupRepo->first(['id' => $gid]);
        if (empty($group)) {
            return back()->with('success_message', 'Không tìm thấy nhóm!');
        }
        $id = $group->project_id;
        $project = $this->projectRepo->first(['id' => $id], [], ['group','admin']);
        $gro = $project->group;
        $gro->load(['phaseGroup' => function($query) use($pid){
        }])->map(function ($gr){
            $gr->phase_qty = $gr->phaseGroup->sum('qty');
        })->keyBy('id');
        if(empty($group)){
            return back()->with('success_message', 'Không tìm thấy nhóm!');
        }
        if(empty($project)){
            return back()->with('success_message', 'Không tìm thấy dự án!');
        }
        // if (!$isAdmin && !in_array($user->id, $project->admin->pluck('id')->all())) {
        //     return back()->with('error_message', 'Bạn không có quyền vào nhóm!');
        // }
        $phase = $this->phase->get(['project_id' => $id], ['id' => 'DESC'])->keyBy('id');
        $pid = $pid > 0 ? $pid : $phase->first()->id;
        $admins = $this->adminRepo->get();
        $design2Admins = $this->role->first(['slug' => 'Design2'], [], ['admin'])->admin->pluck('id')->toArray();
        // $admins = $allAdmins->whereNotIn('id', $design2Admins);
        // $admins = $user->hasRole(['super_admin']) ? $allAdmins : $admins;
        // $admins = $admins->where('id', $user->id)->first() ? $admins : $admins->push($user);
        $params['project_id'] = $id;
        $params['group_id'] = $gid;
        $params['phase_id'] = $pid;
        $data = $this->ticketRepo->get($params, ['deadline_time' => 'asc'], ['admin','creator', 'child.admin'])->map(function ($ticket){
            if ($ticket->status == 0) {
                if ($ticket->deadline_time > time()) {
                    $ticket->status_lb = 'Mới';
                    $ticket->status_cl = 'success';
                } else {
                    $ticket->status_lb = 'Mới';
                    $ticket->status_cl = 'danger';
                }
            } else {
                if ($ticket->deadline_time > $ticket->complete_time) {
                    $ticket->status_lb = 'Hoàn thành';
                    $ticket->status_cl = 'success';
                } else {
                    $ticket->status_lb = 'Hoàn thành';
                    $ticket->status_cl = 'danger';
                }
            }
            return $ticket;
        });
        $notes = $this->note->get(['group_id' => $gid, 'phase_id' => $pid], ['id' => 'DESC'], ['admin']);
        $role = $this->role->getRole();
        $id = $request->input('id', 0);
        return view('admin.ticket.index2', compact('data', 'project', 'admins', 'phase', 'pid', 'gid', 'group', 'isAdmin', 'notes', 'role', 'id', 'isSuperAdmin', 'user', 'design2Admins'));
    }

    public function create(Request $request){
        $user = auth('admin')->user();
        if($user->hasRole(['guest', 'Design', 'Design2', 'content_seo'])){
            return response()->json(['success' => 0, 'mess' => 'Bạn không có quyền!']);
        }
        $params = $request->only('id', 'name', 'description', 'input', 'output', 'status', 'qty', 'priority', 'deadline_time', 'project_id', 'group_id', 'phase_id');
        $params['deadline_time'] = !empty($params['deadline_time']) ? strtotime('tomorrow', strtotime($params['deadline_time'])) - 1 : null;
        $params['status'] = isset($params['status']) ? 1 : 0;
        $params['qty'] = !empty($params['qty']) ? (int)$params['qty'] : 1;
        $params['priority'] = !empty($params['priority']) ? (int)$params['priority'] : 2;
        $admins = $request->get('admin',[]);
        $resA['success'] = 0;
        $resA['mess'] = 'Có lỗi xảy ra!';
        if(isset($params['id'])){
            $ticket = $this->ticketRepo->first(['id' => $params['id']]);
            if($ticket){
                // if ($user->hasRole(['Design', 'Design2'])) {
                //     $status = $params['status'];
                //     $params = [];
                //     $params['status'] = $status;
                // }
                $ticket->load('admin');
                // $isAdmin = $user->hasRole(['super_admin', 'account']);

                // if (!$isAdmin && !in_array($user->id, $ticket->admin->pluck('id')->all()) && $user->id != @$ticket->admin_id_c) {
                //     return back()->with('error_message', 'Bạn không có quyền sửa ticket!');
                // }
                if ($params['status'] == 1) {
                    // $input = $params['input'] ?? $ticket->input;
                    // if (!$input) {
                    //     $resA['mess'] = 'Bạn cần nhập khách duyệt!';
                    //     return response()->json($resA);
                    // }
                    $params['complete_time'] = $ticket->complete_time ?? time();
                } else {
                    $params['complete_time'] = null;
                }
                $res = $this->ticketRepo->update($ticket, $params);
                if($res){
                    $res->admin()->sync($admins);
                    unset($params['group_id']);
                    unset($params['id']);

                    $req = $request->only('is_order');
                    $isOrder = isset($req['is_order']) ? 1 : 0;

                    if ($res->parent_id == 0) {
                        $childAdmin = $request->get('design_handle',[]);

                        $child = $this->ticketRepo->first(['parent_id' => $res->id]);
                        $paramsChild = $request->only('child_input', 'child_output', 'child_status', 'child_qty', 'child_priority', 'child_deadline_time', 'phase_group_id');
                        $paramsC['input'] = @$paramsChild['child_input'];
                        $paramsC['output'] = @$paramsChild['child_output'];
                        $paramsC['status'] = isset($paramsChild['child_status']) ? 1 : 0;
                        $paramsC['qty'] = @$paramsChild['child_qty'];
                        $paramsC['priority'] = @$paramsChild['child_priority'];
                        $paramsC['group_id'] = @$paramsChild['phase_group_id'];
                        $paramsC['deadline_time'] = !empty($paramsChild['child_deadline_time']) ? strtotime('tomorrow', strtotime($paramsChild['child_deadline_time'])) - 1 : null;
                        if ($child) {
                            if ($paramsC['status'] == 1) {
                                // $childOutput = $paramsChild['child_output'] ?? $child->output;
                                // if (!$childOutput) {
                                //     $resA['mess'] = 'Bạn cần nhập sản phẩm để hoàn thành!';
                                //     return response()->json($resA);
                                // }
                                $paramsC['complete_time'] = time();
                            } else {
                                $paramsC['complete_time'] = null;
                            }

                            $resP = $this->ticketRepo->update($child, $paramsC);
                            if ($resP) {
                                $resP->admin()->sync($childAdmin);
                            }
                        } else {
                            if ($isOrder) {
                                // $role = $this->role->first(['slug' => 'Design']);
                                // $group = $this->groupRepo->first(['project_id' => $params['project_id'], 'role_id' => @$role->id], ['id' => 'DESC']);
                                $phaseGroupId = $request->input('phase_group_id', 0);
                                $group = $this->groupRepo->first(['id' => $phaseGroupId]);
                                if ($group) {
                                    $paramsC['name'] = $params['name'];
                                    $paramsC['description'] = $params['description'];
                                    $paramsC['project_id'] = $params['project_id'];
                                    $paramsC['phase_id'] = $params['phase_id'];
                                    $paramsC['created_time'] = time();
                                    $paramsC['parent_id'] = $res->id;
                                    $paramsC['group_id'] = $group->id;
                                    $paramsC['admin_id_c'] = $user->id;
                                    $resChild = $this->ticketRepo->create($paramsC);
                                    if ($resChild) {
                                        $resChild->admin()->sync($childAdmin);
                                    }
                                }
                            }
                        }
                    }



                    $resA['success'] = 1;
                    $resA['mess'] = 'Cập nhật ticket thành công!';
                    return response()->json($resA);
                    // return back()->with('success_message', 'Cập nhật ticket thành công!');
                }
            }
        }
        return response()->json($resA);
        // return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function editNote(Request $request){
        $params = $request->only('id', 'note');
        if(isset($params['id'])){
            $ticket = $this->ticketRepo->first(['id' => $params['id']]);
            if($ticket){
                $res = $this->ticketRepo->update($ticket, $params);
                $this->createNoty($res, 2);
                if($res){
                    return back()->with('success_message', 'Cập nhật ticket thành công!');
                }
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function createAjax(Request $request){
        $user = auth('admin')->user();
        if($user->hasRole(['guest', 'Design', 'Design2'])){
            $res['success'] = 0;
            $res['mess'] = 'Bạn không có quyền!';
            return response()->json($res);
        }
        $params = $request->only('id', 'name', 'description', 'note', 'input', 'output', 'status', 'qty', 'priority', 'deadline_time', 'project_id', 'group_id', 'phase_id');
        $phaseGroup = PhaseGroup::where('phase_id', $params['phase_id'])->where('group_id', $params['group_id'])->first();
        if ($phaseGroup) {
            $count = $this->ticketRepo->get(['phase_id' => $params['phase_id'], 'group_id' => $params['group_id']])->count();
            if ($count >= $phaseGroup->qty) {
                $res['mess'] = 'Quá số lượng hợp đồng';
                return response()->json($res);
            }
        }

        $params['deadline_time'] = !empty($params['deadline_time']) ? strtotime('tomorrow', strtotime($params['deadline_time'])) - 1 : time();
        $today = strtotime('tomorrow', time()) - 1;
        $currentGroup = $this->groupRepo->first(['id' => $params['group_id']]);

        if ($currentGroup) {
            $role = $this->role->first(['id' => $currentGroup->role_id]);

            // Kiểm tra quyền tạo ticket cho Design và Design2
            if ($role && in_array(@$role->slug, ['Design', 'Design2'])) {
                if(!$user->hasRole(['super_admin'])){
                    $res['success'] = 0;
                    $res['mess'] = 'Bạn không có quyền tạo ticket group Thiết kế!';
                    return response()->json($res);
                }

                if ($params['deadline_time'] <= $today) {
                    $res['success'] = 0;
                    $res['mess'] = 'Deadline ít nhất phải ngày mai';
                    return response()->json($res);
                }
            }

            // Kiểm tra quyền tạo ticket cho Content_SEO
            if ($role && in_array(@$role->slug, ['content_seo'])) {
                // Chỉ admin hoặc user từ group SEO mới được tạo
                $isSuperAdmin = $user->hasRole(['super_admin']);
                $isSeoUser = $user->hasRole(['SEO']);

                if (!$isSuperAdmin && !$isSeoUser) {
                    $res['success'] = 0;
                    $res['mess'] = 'Bạn không có quyền tạo ticket group Content SEO!';
                    return response()->json($res);
                }
            }
        }

        $req = $request->only('is_order');

        $isOrder = isset($req['is_order']) ? 1 : 0;
        if ($isOrder) {
            $phaseGroupId = $request->input('phase_group_id', 0);
            $group = $this->groupRepo->first(['id' => $phaseGroupId]);
            if ($group) {
                $paramsChild = $request->only('child_input', 'child_output', 'child_status', 'child_qty', 'child_priority', 'child_deadline_time');
                $deadline = !empty($paramsChild['child_deadline_time']) ? strtotime('tomorrow', strtotime($paramsChild['child_deadline_time'])) - 1 : time();
                $childRole = $this->role->first(['id' => $group->role_id]);
                if ($childRole && in_array(@$childRole->slug, ['Design', 'Design2'])) {
                    if ($deadline <= $today) {
                        $res['mess'] = 'Deadline thiết kế ít nhất phải ngày mai';
                        return response()->json($res);
                    }
                }
            }
        }


        $params['status'] = isset($params['status']) ? 1 : 0;
        $params['qty'] = !empty($params['qty']) ? (int)$params['qty'] : 1;
        $params['priority'] = !empty($params['priority']) ? (int)$params['priority'] : 2;
        $admins = $request->get('admin',[]);

        if ($params['status'] == 1) {
            $params['complete_time'] = time();
        } else {
            $params['complete_time'] = null;
        }
        $params['created_time'] = time();
        $params['admin_id_c'] = $user->id;
        $resC = $this->ticketRepo->create($params);

        $res['success'] = 0;
        $res['mess'] = 'Có lỗi xảy ra!';
        if($resC){
            $resC->admin()->sync($admins);

            if ($isOrder) {
                $handle = $request->input('design_handle', []);

                if ($group) {
                    $paramsChild = $request->only('child_input', 'child_output', 'child_status', 'child_qty', 'child_priority', 'child_deadline_time');
                    $deadline = !empty($paramsChild['child_deadline_time']) ? strtotime('tomorrow', strtotime($paramsChild['child_deadline_time'])) - 1 : time();

                    $paramsC['name'] = $params['name'];
                    $paramsC['description'] = $params['description'];
                    $paramsC['project_id'] = $params['project_id'];
                    $paramsC['phase_id'] = $params['phase_id'];
                    $paramsC['created_time'] = $params['created_time'];
                    $paramsC['input'] = $paramsChild['child_input'];
                    $paramsC['output'] = $paramsChild['child_output'];
                    $paramsC['status'] = isset($paramsChild['child_status']) ? 1 : 0;
                    $paramsC['qty'] = $paramsChild['child_qty'];
                    $paramsC['priority'] = $paramsChild['child_priority'];
                    $paramsC['deadline_time'] = $deadline;
                    $paramsC['parent_id'] = $resC->id;
                    $paramsC['group_id'] = $group->id;
                    $paramsC['admin_id_c'] = $user->id;

                    $resChild = $this->ticketRepo->create($paramsC);
                    if ($resChild) {
                        $resChild->admin()->sync($handle);
                    }
                }
            }
            $res['success'] = 1;
            $res['mess'] = 'Tạo thành công!';
        }
        return response()->json($res);
    }

    public function remove(Request $request){
        $user = auth('admin')->user();
        if($user->hasRole(['guest'])){
            $res['success'] = 0;
            $res['mess'] = 'Bạn không có quyền!';
            return response()->json($res);
        }
        $id = $request->input('id');
        $ticket = $this->ticketRepo->first(['id' => $id]);
        if(empty($ticket)){
            return response()->json(['success' => 0]);
        }
        $isAdmin = $user->hasRole(['super_admin', 'account']);
        if (!$isAdmin && $user->id != @$ticket->admin_id_c) {
            return back()->with('error_message', 'Bạn không có quyền sửa ticket!');
        }
        $resR = $this->ticketRepo->remove($id);
        $res['success'] = 0;
        $res['mess'] = 'Có lỗi xảy ra!';
        if($resR){
            $res['success'] = 1;
            $res['mess'] = 'Xóa thành công!';
        }
        return response()->json($res);
    }

    private function createNoty($data, $type = 1){
        if($type == 1){
            $data = $data->load('group.project.admin');
            $admins = $data->group->project->admin->pluck('id');
            if(!empty($admins)){
                $params = [];
                foreach($admins as $v){
                    $params[] = [
                        'admin_id_c' => $data->admin_id,
                        'project_id' => $data->group->project->id,
                        'group_id' => $data->group_id,
                        'phase_id' => $data->phase_id,
                        'type' => $type,
                        'admin_id' => $v,
                        'status' => 1
                    ];
                }

                if(!empty($params)){
                    $this->noty->createMult($params);
                }
            }
        } else {
            $user = auth('admin')->user();
            $data = $data->load('admin');
            $admins = $data->admin->pluck('id');
            if(!empty($admins)){
                $params = [];
                foreach($admins as $v){
                    $params[] = [
                        'admin_id_c' => $user->id,
                        'project_id' => $data->project_id,
                        'group_id' => $data->group_id,
                        'phase_id' => $data->phase_id,
                        'type' => $type,
                        'admin_id' => $v,
                        'status' => 1
                    ];
                }

                if(!empty($params)){
                    $this->noty->createMult($params);
                }
            }

        }
    }

    public function createNote(Request $request){
        $params = $request->only('note', 'admin_id', 'group_id', 'phase_id');

        $resC = $this->note->create($params);
        $res['success'] = 0;
        if($resC){
            $notes = $this->note->get(['group_id' => $resC->group_id, 'phase_id' => $resC->phase_id], ['id' => 'DESC'], ['admin']);
            $this->createNoty($resC, 1);
            $res['success'] = 1;
            $res['html'] = view('admin.ticket.note', compact('notes'))->render();
        }

        return response()->json($res);
    }

    public function getGoogleSheets(Request $request)
    {
        try {
            $request->validate([
                'google_sheet_url' => 'required|url',
            ]);

            $googleSheetUrl = $request->input('google_sheet_url');

            // Extract the spreadsheet ID from the URL
            preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $googleSheetUrl, $matches);
            if (!isset($matches[1])) {
                return response()->json([
                    'success' => 0,
                    'mess' => 'URL Google Sheets không hợp lệ!'
                ]);
            }

            $spreadsheetId = $matches[1];

            // Fetch spreadsheet metadata to get all sheets
            $metadataUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/edit#gid=0";

            // Use a different approach: Download the Excel file and parse it to get sheet names
            $exportUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=xlsx";

            $tempFile = tempnam(sys_get_temp_dir(), 'google_sheet_meta_');
            $tempFile .= '.xlsx';

            $client = new \GuzzleHttp\Client();
            $response = $client->get($exportUrl, ['sink' => $tempFile]);

            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => 0,
                    'mess' => 'Không thể tải file từ Google Sheets! Vui lòng kiểm tra link và quyền truy cập.'
                ]);
            }

            // Load the Excel file to get sheet names
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tempFile);
            $sheets = [];

            foreach ($spreadsheet->getAllSheets() as $index => $sheet) {
                $sheets[] = [
                    'title' => $sheet->getTitle(),
                    'gid' => $index, // Excel doesn't use gid, we'll use index
                    'index' => $index
                ];
            }

            // Clean up temp file
            unlink($tempFile);

            return response()->json([
                'success' => 1,
                'sheets' => $sheets,
                'spreadsheet_id' => $spreadsheetId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'mess' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }

    public function importFromGoogleSheet(Request $request)
    {
        try {
            $user = auth('admin')->user();

            // Validate required parameters
            $request->validate([
                'google_sheet_url' => 'required|url',
                'project_id' => 'required|exists:project,id',
                'group_id' => 'required|exists:group,id',
                'phase_id' => 'required|exists:phase,id',
            ]);

            $googleSheetUrl = $request->input('google_sheet_url');
            $projectId = $request->input('project_id');
            $groupId = $request->input('group_id');
            $phaseId = $request->input('phase_id');
            $sheetGid = $request->input('sheet_gid'); // Get selected sheet

            // Kiểm tra quyền import theo group
            $currentGroup = $this->groupRepo->first(['id' => $groupId]);
            if ($currentGroup) {
                $role = $this->role->first(['id' => $currentGroup->role_id]);

                // Kiểm tra quyền cho Design và Design2
                if ($role && in_array(@$role->slug, ['Design', 'Design2'])) {
                    if(!$user->hasRole(['super_admin'])){
                        return response()->json([
                            'success' => 0,
                            'mess' => 'Bạn không có quyền import ticket cho group Thiết kế!'
                        ]);
                    }
                }

                // Kiểm tra quyền cho Content_SEO
                if ($role && in_array(@$role->slug, ['content_seo'])) {
                    $isSuperAdmin = $user->hasRole(['super_admin']);
                    $isSeoUser = $user->hasRole(['SEO']);

                    if (!$isSuperAdmin && !$isSeoUser) {
                        return response()->json([
                            'success' => 0,
                            'mess' => 'Bạn không có quyền import ticket cho group Content SEO!'
                        ]);
                    }
                }
            }

            // Extract the spreadsheet ID from the URL
            preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $googleSheetUrl, $matches);
            if (!isset($matches[1])) {
                return response()->json([
                    'success' => 0,
                    'mess' => 'URL Google Sheets không hợp lệ!'
                ]);
            }

            $spreadsheetId = $matches[1];

            // Download the entire workbook
            $exportUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=xlsx";

            $tempFile = tempnam(sys_get_temp_dir(), 'google_sheet_');
            $tempFile .= '.xlsx';

            $client = new \GuzzleHttp\Client();
            $response = $client->get($exportUrl, ['sink' => $tempFile]);
            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => 0,
                    'mess' => 'Không thể tải file từ Google Sheets!'
                ]);
            }

            // Import the data with selected sheet index
            $sheetIndex = ($sheetGid !== null && $sheetGid !== '') ? intval($sheetGid) : null;
            $import = new \App\Imports\Ticket\TicketImport($projectId, $groupId, $phaseId, $user->id, $sheetIndex);
            \Maatwebsite\Excel\Facades\Excel::import($import, $tempFile);

            // Clean up temp file
            unlink($tempFile);

            return response()->json([
                'success' => 1,
                'mess' => 'Import dữ liệu thành công!',
                'redirect' => route('admin.ticket.index', ['gid' => $groupId, 'pid' => $phaseId])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'mess' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
}

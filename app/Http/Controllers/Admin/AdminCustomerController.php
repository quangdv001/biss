<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Customer\CustomerExport;
use App\Http\Controllers\Controller;
use App\Imports\Customer\CustomerImport;
use App\Repo\AdminRepo;
use App\Repo\CustomerRepo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class AdminCustomerController extends Controller
{
    private $customer, $admin;
    public function __construct(CustomerRepo $customer, AdminRepo $admin)
    {
        $this->customer = $customer;
        $this->admin = $admin;
    }

    public function index(Request $request)
    {
        $request->flash();
        $params = $request->only('name', 'phone', 'admin_id', 'start_time', 'end_time', 'status');
        $limit = $request->input('limit', 50);
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                if (!$v) {
                    unset($params[$k]);
                } else {

                    if (in_array($k, ['name', 'phone'])) {
                        $search = [$k, 'like', '%' . $v . '%'];
                        unset($params[$k]);
                        $params[] = $search;
                    }
    
                    if ($k == 'start_time') {
                        $start = strtotime($v);
                        $params[] = ['start_time', '>=', $start]; 
                        unset($params[$k]);
                    }
                    if ($k == 'end_time') {
                        $end = strtotime($v) + 86399;
                        $params[] = ['start_time', '<=', $end];
                        unset($params[$k]);
                    }
                }

            }
        }
        $user = auth('admin')->user();
        $isSuperAdmin = $user->hasRole(['super_admin']);
        if(!$user->hasRole(['super_admin'])) {
            $params['admin_id'] = $user->id;
        }
        $data = $this->customer->paginate($params, $limit, ['updated_at' => 'DESC', 'start_time' => 'DESC'], ['admin']);
        $admins = $this->admin->getAdmin();
        $status = [
            1 => [
                'class' => 'info',
                'text' => 'Mới'
            ],
            2 => [
                'class' => 'success',
                'text' => 'Hoàn thành'
            ],
            3 => [
                'class' => 'danger',
                'text' => 'Hủy'
            ],
        ];
        $source = ['CRM', 'Cuộc gọi mới', 'Khách hàng hiện tại', 'Tự tìm', 'Nhân viên', 'Đối tác', 'Quan hệ công chúng', 'Thư trực tiếp', 'Hội nghị', 'Triển lãm thương mại', 'Website', 'Truyền thông', 'Facebook', 'Google', 'SEO', 'Khác'];
        return view('admin.customer.index', compact('data', 'admins', 'status', 'source', 'isSuperAdmin'));
    }

    public function create(Request $request)
    {
        $user = auth('admin')->user();
        if (!$user->hasRole(['super_admin', 'account'])) {
            return back()->with('error_message', 'Bạn không có quyền quản lý dự án!');
        }
        $params = $request->only('id','name', 'description', 'note', 'response', 'phone', 'email', 'province', 'title', 'company', 'admin_id', 'start_time', 'status', 'source');
        $params['start_time'] = $params['start_time'] ? strtotime($params['start_time']) : null;
        if (isset($params['id'])) {
            $customer = $this->customer->find($params['id']);
            if ($customer) {
                $res = $this->customer->update($customer, $params);
                if ($res) {
                    return back()->with('success_message', 'Cập nhật Khách hàng thành công!');
                }
            }
        } else {
            $params['created_time'] = time();
            $res = $this->customer->create($params);
            if ($res) {
                return back()->with('success_message', 'Tạo dự án thành công!');
            }
        }
        return back()->with('error_message', 'Có lỗi xảy ra!');
    }

    public function remove(Request $request)
    {
        $user = auth('admin')->user();
        if(!$user->hasRole(['super_admin', 'account'])){
            return response(['success' => 0]);
        }
        $id = $request->input('id');
        $resR = $this->customer->destroy($id);
        $res['success'] = 0;
        if($resR){
            $res['success'] = 1;
        }
        return response()->json($res);
    }

    public function export(Request $request)
    {
        $params = $request->only('id', 'name', 'phone', 'admin_id', 'start_time', 'status');
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                if (!$v) {
                    unset($params[$k]);
                }

                if (in_array($k, ['name', 'phone'])) {
                    $search = [$k, 'like', '%' . $v . '%'];
                    unset($params[$k]);
                    $params[] = $search;
                }

                if ($k == 'start_time') {
                    $arrTime = explode(' - ', $v);
                    if ($arrTime[0] == $arrTime[1]) {
                        unset($params[$k]);
                    } else {
                        $start = strtotime($arrTime[0]);
                        $end = strtotime($arrTime[1]) + 86399;
                        unset($params[$k]);
                        $params[] = ['created_time', '>=', $start]; 
                        $params[] = ['created_time', '<=', $end];
                    }
                }
            }
        }

        return Excel::download(new CustomerExport($params), 'Khách hàng.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new CustomerImport, request()->file('file'));
        $res['success'] = 1;
        return response()->json($res);
    }
    
}

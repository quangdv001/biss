<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repo\AdminRepo;
use App\Repo\CustomerRepo;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    private $customer, $admin;
    public function __construct(CustomerRepo $customer, AdminRepo $admin)
    {
        $this->customer = $customer;
        $this->admin = $admin;
    }

    public function index(Request $request){
        $request->flash();
        $params = $request->only('name', 'phone', 'admin_id', 'source', 'status');
        $limit = $request->input('limit', 50);
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
            }
        }

        $data = $this->customer->paginate($params, $limit, ['id' => 'DESC'], ['admin']);
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
        return view('admin.customer.index', compact('data', 'admins', 'status', 'source'));
    }

    public function create(Request $request){
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

    public function remove(Request $request){
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
}

<?php

namespace App\Exports\Customer;

use Illuminate\Contracts\View\View;
use App\Repo\CustomerRepo;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    private $params;
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
    }

    public function view(): View
    {
        $repo = app(CustomerRepo::class);
        $data = $repo->get($this->params, ['id' => 'DESC'], ['admin']);
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
        return view('admin.customer.export', compact('data', 'status', 'source'));
    }
}

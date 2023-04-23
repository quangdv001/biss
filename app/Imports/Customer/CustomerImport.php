<?php

namespace App\Imports\Customer;

use App\Repo\AdminRepo;
use App\Repo\CustomerRepo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomerImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        $collection->forget(0);
        if ($collection->count()) {
            $admin = app(AdminRepo::class);
            $admins = $admin->getAdmin()->keyBy('username');
            $data = [];
            foreach ($collection as $v) {
                $time = $v[5] ? Date::excelToDateTimeObject($v[6])->getTimestamp() : time();
                $data[] = [
                    'name' => @$v[0],
                    'company' => @$v[1],
                    'phone' => @$v[2],
                    'title' => @$v[3],
                    'email' => @$v[4],
                    'admin_id' => @$admins[$v[5]]->id,
                    'created_time' => $time,
                    'source' => 0,
                    'province' => @$v[7],
                    'description' => @$v[8],
                    'status' => 1,
                ];
            }
            
            if (!empty($data)) {
                foreach (array_chunk($data, 1000) as $t) {
                    $repo = app(CustomerRepo::class)->insert($t);
                }
            }
        }
    }
}

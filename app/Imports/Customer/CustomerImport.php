<?php

namespace App\Imports\Customer;

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
            $data = [];
            foreach ($collection as $v) {
                $time = $v[5] ? Date::excelToDateTimeObject($v[5])->getTimestamp() : time();
                $data[] = [
                    'name' => @$v[0],
                    'company' => @$v[1],
                    'phone' => @$v[2],
                    'title' => @$v[3],
                    'email' => @$v[4],
                    'created_time' => $time,
                    'source' => 0,
                    'province' => @$v[6],
                    'description' => @$v[7],
                    'status' => 1,
                ];
            }

            $repo = app(CustomerRepo::class)->insert($data);
        }
    }
}

<?php
namespace App\Repo;

use App\Models\Customer;

class CustomerRepo extends BaseRepo
{
    public function model()
    {
        return Customer::class;
    }
}
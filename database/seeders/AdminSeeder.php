<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([[
            'username' => 'admin',
            'password' => Hash::make('admin')
        ],[
            'username' => 'guest',
            'password' => Hash::make('guest')
        ],[
            'username' => 'account',
            'password' => Hash::make('account')
        ],[
            'username' => 'seo',
            'password' => Hash::make('seo')
        ]]);
    }
}

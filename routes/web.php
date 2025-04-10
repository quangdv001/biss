<?php

use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.home.index');
});

Route::get('/test', function () {
    $result = OpenAI::chat()->create([
        "model" => "gpt-4o-mini",
        'messages' => [
            ['role' => 'user', 'content' => 'Giới thiệu về BissBrand ở Việt Nam'],
        ],
    ]);

    dd($result);
});

Route::namespace('Admin')->name('admin.')->prefix('admin')->group(function () {
    require "admin.php";
});

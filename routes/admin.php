<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'AdminAuthController@login')->name('auth.login');
Route::post('login', 'AdminAuthController@postLogin')->name('auth.postLogin');
Route::get('logout', 'AdminAuthController@logout')->name('auth.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', 'AdminHomeController@index')->name('home.index');

    Route::get('profile', 'AdminProfileController@index')->name('profile.index');
    Route::post('profile', 'AdminProfileController@postIndex')->name('profile.postIndex');
    Route::get('profile/password', 'AdminProfileController@password')->name('profile.password');
    Route::post('profile/password', 'AdminProfileController@postPassword')->name('profile.postPassword');

    Route::get('role', 'AdminRoleController@index')->name('role.index');
    Route::post('role/create', 'AdminRoleController@create')->name('role.create');
    Route::post('role/remove', 'AdminRoleController@remove')->name('role.remove');
    
    Route::get('account', 'AdminAccountController@index')->name('account.index');
    Route::post('account/create', 'AdminAccountController@create')->name('account.create');
    Route::post('account/changePass', 'AdminAccountController@changePass')->name('account.changePass');
    Route::post('account/remove', 'AdminAccountController@remove')->name('account.remove');

    Route::get('project', 'AdminProjectController@index')->name('project.index');
    Route::post('project/create', 'AdminProjectController@create')->name('project.create');
    Route::post('project/remove', 'AdminProjectController@remove')->name('project.remove');

    Route::get('group', 'AdminGroupController@index')->name('group.index');
    Route::post('group/create', 'AdminGroupController@create')->name('group.create');
    Route::post('group/remove', 'AdminGroupController@remove')->name('group.remove');

});

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
});

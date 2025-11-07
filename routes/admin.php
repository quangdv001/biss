<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'AdminAuthController@login')->name('auth.login');
Route::post('login', 'AdminAuthController@postLogin')->name('auth.postLogin');
Route::get('logout', 'AdminAuthController@logout')->name('auth.logout');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/', 'AdminHomeController@index')->name('home.index');
    Route::get('dashboard', 'AdminHomeController@dashboard')->name('home.dashboard');
    Route::get('intro', 'AdminHomeController@intro')->name('home.intro');
    Route::get('calendar', 'AdminHomeController@calendar')->name('home.calendar');
    Route::post('home/getNoty', 'AdminHomeController@getNoty')->name('home.getNoty');
    Route::post('home/detailNoty', 'AdminHomeController@detailNoty')->name('home.detailNoty');
    Route::post('home/viewNoty', 'AdminHomeController@viewNoty')->name('home.viewNoty');
    Route::post('home/getPersonalReport', 'AdminHomeController@getPersonalReport')->name('home.getPersonalReport');
    Route::post('home/getProjectReport', 'AdminHomeController@getProjectReport')->name('home.getProjectReport');
    Route::get('home/getCalendarData', 'AdminHomeController@getCalendarData')->name('home.getCalendarData');
    Route::get('home/personal-calendar/{adminId?}', 'AdminHomeController@personalCalendar')->name('home.personalCalendar');
    Route::get('home/getPersonalCalendarData', 'AdminHomeController@getPersonalCalendarData')->name('home.getPersonalCalendarData');

    Route::get('profile', 'AdminProfileController@index')->name('profile.index');
    Route::post('profile', 'AdminProfileController@postIndex')->name('profile.postIndex');
    Route::get('profile/password', 'AdminProfileController@password')->name('profile.password');
    Route::post('profile/password', 'AdminProfileController@postPassword')->name('profile.postPassword');

    Route::get('role', 'AdminRoleController@index')->name('role.index');
    Route::post('role/create', 'AdminRoleController@create')->name('role.create');
    Route::post('role/remove', 'AdminRoleController@remove')->name('role.remove');
    Route::get('role/report', 'AdminRoleController@report')->name('role.report');
    Route::get('role/report2', 'AdminRoleController@report2')->name('role.report2');
    Route::get('role/report3', 'AdminRoleController@report3')->name('role.report3');

    Route::get('account', 'AdminAccountController@index')->name('account.index');
    Route::post('account/create', 'AdminAccountController@create')->name('account.create');
    Route::post('account/changePass', 'AdminAccountController@changePass')->name('account.changePass');
    Route::post('account/remove', 'AdminAccountController@remove')->name('account.remove');
    Route::get('account/report', 'AdminAccountController@report')->name('account.report');

    Route::get('project', 'AdminProjectController@index')->name('project.index');
    Route::post('project/create', 'AdminProjectController@create')->name('project.create');
    Route::post('project/remove', 'AdminProjectController@remove')->name('project.remove');

    Route::get('group/{id}/{pid?}', 'AdminGroupController@index')->name('group.index');
    Route::post('group/create', 'AdminGroupController@create')->name('group.create');
    Route::post('group/createPhase', 'AdminGroupController@createPhase')->name('group.createPhase');
    Route::post('group/remove', 'AdminGroupController@remove')->name('group.remove');
    Route::post('group/removePhase', 'AdminGroupController@removePhase')->name('group.removePhase');

    Route::get('ticket/{gid}/{pid?}', 'AdminTicketController@index')->name('ticket.index');
    Route::post('ticket/create', 'AdminTicketController@create')->name('ticket.create');
    Route::post('ticket/createAjax', 'AdminTicketController@createAjax')->name('ticket.createAjax');
    Route::post('ticket/remove', 'AdminTicketController@remove')->name('ticket.remove');
    Route::post('ticket/bulk-remove', 'AdminTicketController@bulkRemove')->name('ticket.bulkRemove');
    Route::post('ticket/createNote', 'AdminTicketController@createNote')->name('ticket.createNote');
    Route::post('ticket/editNote', 'AdminTicketController@editNote')->name('ticket.editNote');
    Route::post('ticket/get-google-sheets', 'AdminTicketController@getGoogleSheets')->name('ticket.getGoogleSheets');
    Route::post('ticket/import-google-sheet', 'AdminTicketController@importFromGoogleSheet')->name('ticket.importGoogleSheet');
    Route::post('ticket/generate-auto-post-token', 'AdminTicketController@generateAutoPostToken')->name('ticket.generateAutoPostToken');

    Route::get('customer', 'AdminCustomerController@index')->name('customer.index');
    Route::post('customer/create', 'AdminCustomerController@create')->name('customer.create');
    Route::post('customer/remove', 'AdminCustomerController@remove')->name('customer.remove');
    Route::get('customer/export', 'AdminCustomerController@export')->name('customer.export');
    Route::post('customer/import', 'AdminCustomerController@import')->name('customer.import');

    Route::get('ai', 'AdminAiController@index')->name('ai.index');
    Route::post('ai/send', 'AdminAiController@send')->name('ai.send');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Project API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Get list of projects with filters
    Route::get('/projects', [ProjectApiController::class, 'index'])->name('api.projects.index');

    // Get list of groups by project_id
    Route::get('/projects/{projectId}/groups', [ProjectApiController::class, 'groups'])->name('api.projects.groups');

    // Get list of tickets by group_id
    Route::get('/groups/{groupId}/tickets', [ProjectApiController::class, 'tickets'])->name('api.groups.tickets');

    // Get ticket detail by id
    Route::get('/tickets/{ticketId}', [ProjectApiController::class, 'ticketDetail'])->name('api.tickets.detail');

    // Update ticket
    Route::put('/tickets/{ticketId}', [ProjectApiController::class, 'updateTicket'])->name('api.tickets.update');
    Route::patch('/tickets/{ticketId}', [ProjectApiController::class, 'updateTicket'])->name('api.tickets.patch');
});

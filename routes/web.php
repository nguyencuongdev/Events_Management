<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\EventController;
use  App\Http\Controllers\AuthController;
use  App\Http\Controllers\ChannelController;
use  App\Http\Controllers\RoomController;
use  App\Http\Controllers\SessionController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/report/events/{slug}', [ReportController::class, 'reportCapacityRoom']);
Route::get('/', [EventController::class, 'index']);
Route::resource('events', EventController::class);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'handleLogin']);
Route::get('/logout', [AuthController::class, 'handleLogout']);

Route::resource('events.tickets', EventTicketController::class)->shallow();
Route::resource('events.channels', ChannelController::class)->shallow();
Route::resource('events.rooms', RoomController::class)->shallow();
Route::resource('events.sessions', SessionController::class)->shallow();

Route::fallback(function () {
    return view('error.404');
});

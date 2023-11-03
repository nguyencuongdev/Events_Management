<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationEventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/events', [EventController::class, 'handleGetEvents']);
Route::get(
    '/organizers/{organizer_slug}/events/{event_slug}',
    [EventController::class, 'handleGetInforDetailEvent']
);
Route::post('/login', [AuthController::class, 'handleLoginClient']);
Route::post('/logout', [AuthController::class, 'handleLogoutClient']);

Route::post(
    '/organizer/{organizer_slug}/events/{event_slug}/registration',
    [RegistrationEventController::class, 'handleRegistrationEvent']
);

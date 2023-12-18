<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\EventController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\RegistrationController;
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

Route::get('/events', [EventController::class, 'getEvents']);
Route::get(
    '/organizers/{organizer_slug}/events/{event_slug}',
    [EventController::class, 'getInforDetailEvent']
);

Route::post('/login', [AuthController::class, 'handleLoginClient']);
Route::post('/logout', [AuthController::class, 'handleLogoutClient']);

Route::post(
    '/organizers/{organizer_slug}/events/{event_slug}/registration',
    [RegistrationController::class, 'registrationEvent']
);
Route::get('/registrations', [RegistrationController::class, 'getRegistedEvents']);

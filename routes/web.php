<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ChannelController;



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

// Route for event management
Route::get('/', [
    EventController::class,
    'index'
]);

Route::get('/event/create', [
    EventController::class,
    'createEvent'
]);

Route::post('/event/create', [
    EventController::class,
    'handleCreateEvent'
]);

Route::get('/event/detail/{slug}', [
    EventController::class,
    'detailEvent'
])->where('slug', '[a-z0-9-]+');

Route::get('/event/edit/{slug}', [
    EventController::class,
    'editEvent'
])->where('slug', '[a-z0-9-]+');

Route::put('/event/edit/{slug}', [
    EventController::class,
    'handleEditEvent'
])->where('slug', '[a-z0-9-]+');


//route for tickets event management
Route::get('/event/new/ticket/{slug}', [
    TicketController::class,
    'createTicket'
])->where('slug', '[a-z0-9-]+');

Route::post('/event/new/ticket/{slug}', [
    TicketController::class,
    'handleCreateTicket'
])->where('slug', '[a-z0-9-]+');

//route for sessions event mamgement
Route::get(
    '/event/new/session/{slug}',
    [
        SessionController::class,
        'createSession'
    ]
)->where('slug', '[a-z0-9-]+');

Route::post(
    '/event/new/session/{slug}',
    [
        SessionController::class,
        'handleCreateSession'
    ]
)->where('slug', '[a-z0-9-]+');

Route::get(
    '/event/{slug}/session/{id}',
    [
        SessionController::class,
        'editSession'
    ]
)->where([
    'slug' => '[a-z0-9-]+',
    'id' => '\d+'
]);

Route::put(
    '/event/{slug}/session/{id}',
    [
        SessionController::class,
        'handleEditSession'
    ]
)->where([
    'slug' => '[a-z0-9-]+',
    'id' => '\d+'
]);

Route::get(
    '/event/{slug}/new/room',
    [
        RoomController::class,
        'createRoom'
    ]
)->where('slug', '[a-z0-9-]+');

Route::post(
    '/event/{slug}/new/room',
    [
        RoomController::class,
        'handleCreateRoom'
    ]
)->where('slug', '[a-z0-9-]+');

Route::get(
    '/event/{slug}/new/channel',
    [
        ChannelController::class,
        'createChannel'
    ]
)->where('slug', '[a-z0-9-]+');

Route::post(
    '/event/{slug}/new/channel',
    [
        ChannelController::class,
        'handleCreateChannel'
    ]
)->where('slug', '[a-z0-9-]+');

//router for auth
Route::get('/login', [
    AuthController::class,
    'login'
]);
Route::post('/login', [
    AuthController::class,
    "handleLogin"
]);
Route::get('/logout', [
    AuthController::class,
    "handleLogout"
]);

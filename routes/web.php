<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;


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
Route::get('/',[
    EventController::class,
    'index'
] );

Route::get('/event/create',[
    EventController::class,
    'create'
]);

Route::get('/event/detail/{slug}',[
    EventController::class,
    'detail'
])->where('slug','[a-z0-9-]+');

Route::post('/event/create',[
    EventController::class,
    'handleCreateEvent'
]);


//router for auth
Route::get('/login',[
    AuthController::class,
    'login'
]);
Route::post('/login',[
    AuthController::class,
    "handleLogin"
]);
Route::get('/logout',[
     AuthController::class,
    "handleLogout"
]);
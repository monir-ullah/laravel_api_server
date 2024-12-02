<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
   return response()->json(['stack' => [
        'driver' => 'stack',
        'name' => 'channel-name',
        'channels' => ['single', 'slack'],
    ]]);
});

Route::post('/register_user', [UserController::class,'register_user']);
Route::post('/login_user', [AuthController::class,'login_user']);
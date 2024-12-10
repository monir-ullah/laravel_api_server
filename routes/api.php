<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
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

Route::prefix('authors')->group(function () {
    Route::post('/create_author', [AuthorController::class, 'create_author']); // Create Author
    Route::get('/get_authors', [AuthorController::class, 'get_authors']);  // Get All Authors
    Route::get('/get_top_authors', [AuthorController::class, 'getTopFourAuthorsSortedByReview']);  // Get top four authors

     
    Route::get('/get_single_author/{id}', [AuthorController::class, 'get_single_author_by_id']);

});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

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


Route::group(['prefix' => 'auth'], function(){
    Route::post('/login',[UserController::class, 'login'])->name('login');
    Route::post('/register', [UserController::class, 'register']);
});

Route::group(['middleware' => 'auth:api'], function(){
    //api user
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/profile', [UserController::class, 'profile']);
    Route::post('/users/{user}', [UserController::class, 'update']);

    //api post
    Route::get('/post', [PostController::class, 'index']);
    Route::post('/post', [PostController::class, 'store']);
});
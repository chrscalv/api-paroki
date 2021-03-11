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

Route::get('/post/all', [PostController::class, 'all']);

Route::middleware('auth:api')->group(function(){
    //api post
    Route::get('/post', [PostController::class, 'index']);
    Route::get('/post/{slug}', [PostController::class, 'show']);
    Route::post('/post', [PostController::class, 'store']);
    Route::post('/post/{id}/published', [PostController::class, 'published']);
    Route::post('/post/{id}/arcvihed', [PostController::class, 'archived']);
    Route::post('/post/{slug}', [PostController::class, 'update']);
    Route::delete('/post/{slug}', [PostController::class, 'destroy']);

    //api category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{slug}', [CategoryController::class, 'show']);
    Route::post('/category/{slug}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [Categorycontroller::class, 'delete']);

    //api user
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/register', [UserController::class, 'register']);
});

Route::group(['prefix' => 'auth'], function(){
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

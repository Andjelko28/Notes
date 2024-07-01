<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;


Route::post('/login', [LoginController::class, 'login']);
Route::delete('/logout', [LoginController::class, 'logout']);
Route::post('/register', [RegisterController::class, 'register']);

Route::resource('todos', TodoController::class)->middleware('auth:api');
Route::resource('todos.items', ItemController::class)->middleware('auth:api');



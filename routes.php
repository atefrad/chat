<?php

use App\Core\Routing\Route;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;


Route::get('/', [HomeController::class, 'index']);

Route::get('/register-form', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login-form', [LoginController::class, 'create']);
Route::post('/login', [LoginController::class, 'store']);

Route::get('/chats', [ChatsController::class, 'index']);
Route::get('/chats/messages', [MessageController::class, 'index']);
Route::post('/chats/messages/store', [MessageController::class, 'store']);
Route::get('/chats/messages/edit', [MessageController::class, 'edit']);
Route::post('/chats/messages/update', [MessageController::class, 'update']);
Route::get('/chats/messages/delete', [MessageController::class, 'destroy']);
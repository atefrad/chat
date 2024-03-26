<?php

use App\Core\Routing\Route;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserBlockController;
use App\Http\Controllers\UserController;

//home
Route::get('/', [HomeController::class, 'index']);

//register
Route::get('/register-form', [RegisterController::class, 'create']);
Route::post('/register', [RegisterController::class, 'store']);

//login
Route::get('/login-form', [LoginController::class, 'create']);
Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class, 'logout']);

//chats
Route::get('/chats', [ChatsController::class, 'index']);
Route::get('/chats/messages', [MessageController::class, 'index']);
Route::post('/chats/messages/store', [MessageController::class, 'store']);
Route::get('/chats/messages/edit', [MessageController::class, 'edit']);
Route::post('/chats/messages/update', [MessageController::class, 'update']);
Route::get('/chats/messages/delete', [MessageController::class, 'destroy']);

//users
Route::get('/users/delete', [UserController::class, 'destroy']);
Route::get('/users/block', [UserBlockController::class, 'store']);

//messages-ajax
Route::post('/chats/messages/ajax/store', [MessageController::class, 'ajaxStore']);
Route::get('/chats/messages/ajax/last', [MessageController::class, 'lastMessage']);
Route::get('/chats/messages/ajax/edit', [MessageController::class, 'ajaxEdit']);
Route::post('/chats/messages/ajax/update', [MessageController::class, 'ajaxUpdate']);
Route::post('/chats/messages/ajax/delete', [MessageController::class, 'ajaxDestroy']);
Route::get('/chats/messages/ajax/seen-messages', [MessageController::class, 'ajaxSeen']);

//not found
Route::get('/not-found', [ErrorController::class, 'index']);

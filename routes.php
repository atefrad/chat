<?php

use App\Core\Routing\Route;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserBlockController;
use App\Http\Controllers\UserController;


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

Route::get('/users/delete', [UserController::class, 'destroy']);
Route::get('/users/block', [UserBlockController::class, 'store']);
<<<<<<< HEAD
=======

Route::post('/chats/ajax/store', [MessageController::class, 'ajaxStore']);
Route::get('/chats/ajax/last', [MessageController::class, 'lastMessage']);
>>>>>>> 9e9b44a4b6cae7ac572cc09bdd86875512d486b9

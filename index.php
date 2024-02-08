<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes.php';

use App\Core\Routing\Route;

Route::find($_SERVER['PATH_INFO'] ?? '/', strtolower($_SERVER['REQUEST_METHOD']));
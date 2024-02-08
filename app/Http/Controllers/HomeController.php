<?php

namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
        (new ChatsController())->index();
    }
}
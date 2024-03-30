<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class UserBlockController
{
    public function store()
    {
        global $queryBuilder;

        $queryBuilder->table('users')
            ->update(['status'])
            ->where('id', $_GET['user_id'], '=')
            ->execute(['status' => 0]);

        header("Location: /chats/messages?id=" . $_GET['chat_id']);
    }
}
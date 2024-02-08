<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class UserBlockController
{
    public function store()
    {
        $connection = MysqlConnection::getInstance();

        $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));

        $queryBuilder = new MysqlQueryBuilder($connection);

        $queryBuilder->table('users')
            ->update(['status'])
            ->where('id', $_GET['user_id'], '=')
            ->execute(['status' => 0]);

        header("Location: /chats/messages?id=" . $_GET['chat_id']);
    }
}
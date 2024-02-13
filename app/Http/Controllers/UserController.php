<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class UserController
{
    private MysqlQueryBuilder $queryBuilder;

    public function __construct()
    {
        global $queryBuilder;

        $this->queryBuilder = $queryBuilder;
    }

    public function destroy()
    {

        $this->queryBuilder->table('users')
            ->delete()
            ->where('id', $_GET['user_id'], '=')
            ->execute();

        header("Location: /chats/messages?id=" . $_GET['chat_id']);
    }
}
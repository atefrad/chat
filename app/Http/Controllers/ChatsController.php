<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class ChatsController
{
    public function index()
    {

        $connection = MysqlConnection::getInstance();

        $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));

        $queryBuilder = new MysqlQueryBuilder($connection);

        (new LoginController)->checkLogin();

        $chats = $queryBuilder->table('chats')->select()->execute()->fetchAll();

        $chatsLastMessage = [];

        foreach($chats as $chat)
        {
            $chatsLastMessage[] = $queryBuilder->table('messages')
                ->select()
                ->where('chat_id', $chat->id, '=')
                ->orderBy('created_at', 'DESC')
                ->limit(1)
                ->execute()
                ->fetch();
        }

        require_once "./views/chat/index.view.php";
    }
}
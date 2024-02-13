<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class ChatsController
{
    public function index()
    {
        global $queryBuilder;

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
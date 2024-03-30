<?php

namespace App\Events;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class MessageHasBeenSeen
{
//    public static function run(object $message)
//    {
//        if($_SESSION['user']->id !== $message->user_id && $_SERVER['REQUEST_URI'] === "/chats/messages?id=$message->chat_id")
//        {
//            $connection = MysqlConnection::getInstance();
//
//            $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));
//
//            $queryBuilder = new MysqlQueryBuilder($connection);
//
//            $queryBuilder->table('messages')
//                ->update(['seen'])
//                ->where('id', $message->id,'=')
//                ->execute(['seen' => 1]);
//        }
//    }

    public static function ajaxRun(array $ids)
    {
        global $queryBuilder;

        $idsString = '(' . implode(', ', $ids) . ')';

        $queryBuilder->table('messages')
            ->update(['seen'])
            ->where('id', $idsString, 'IN')
            ->execute(['seen' => 1]);
    }
}
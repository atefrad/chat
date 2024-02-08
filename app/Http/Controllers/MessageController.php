<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use App\Core\Validations\ImageType;
use App\Core\Validations\Max;
use App\Core\Validations\Validation;
use App\Events\MessageHasBeenSeen;
use PDO;

date_default_timezone_set('Asia/Tehran');
class MessageController
{
    private MysqlQueryBuilder $queryBuilder;

    public function __construct()
    {
        $connection = MysqlConnection::getInstance();

        $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));

        $this->queryBuilder = new MysqlQueryBuilder($connection);
    }

    public function index()
    {
        (new LoginController)->checkLogin();

        $messages = $this->queryBuilder->table('messages')
            ->select(['messages.*', 'chats.name AS chat_name', 'chats.image AS chat_image', 'users.username AS username'])
            ->join('JOIN', 'chats')
            ->on('messages.chat_id', 'chats.id')
            ->join('JOIN', 'users')
            ->on('messages.user_id', 'users.id')
            ->where('chat_id', $_GET['id'], '=')
            ->orderBy('created_at')
            ->execute()
            ->fetchAll();

        $_SESSION['messages'] = $messages;

        require_once "./views/chat/message/index.view.php";
    }

    public function store()
    {
        if(!empty($_FILES['image']['tmp_name']))
        {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
                'image'=> [new ImageType]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Max(100)],
            ];

            $request = array_intersect_key($_REQUEST, $rules);
        }

        if(Validation::make($request,$rules)){
            //store-message

            if(!empty($_FILES['image']['tmp_name'])){

                $image = (new ImageController)->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            }else{
                $image = '';
            }

            $this->queryBuilder->table('messages')
                ->insert(['user_id', 'chat_id', 'body', 'image', 'created_at'])
                ->execute([
                    'user_id' => $_REQUEST['user_id'],
                    'chat_id' => $_REQUEST['chat_id'],
                    'body' => $_REQUEST['body'],
                    'image' => $image,
                    'created_at' => date('Y_m_d H:i:s', time())
                ]);
        }

        header("Location: /chats/messages?id=" . $_REQUEST['chat_id']);

    }

    public function edit()
    {
        $message = $this->queryBuilder->table('messages')
            ->select()
            ->where('id',$_GET['id'], '=')
            ->execute()
            ->fetch();

        $_SESSION['editedMessage'] = $message;

       header("Location: /chats/messages?id=$message->chat_id");
    }

    public function update()
    {
        if(!empty($_FILES['image']['tmp_name']))
        {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
                'image'=> [new ImageType]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Max(100)],
            ];

            $request = array_intersect_key($_REQUEST, $rules);
        }

        if(Validation::make($request,$rules)){
            //update message

            if(!empty($_FILES['image']['tmp_name'])){

                $image = (new ImageController)->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            }else{
                $image = '';
            }

            $this->queryBuilder->table('messages')
                ->update(['body', 'image', 'updated_at'])
                ->where('id', $_GET['id'], '=')
                ->execute([
                    'body' => $_REQUEST['body'],
                    'image' => $image,
                    'updated_at' => date('Y_m_d H:i:s', time())
                ]);
        }

        header("Location: /chats/messages?id=" . $_REQUEST['chat_id']);

    }

    public function destroy()
    {
        $message = $this->queryBuilder->table('messages')
            ->select()
            ->where('id', $_GET['id'], '=')
            ->execute()
            ->fetch();

        $this->queryBuilder->table('messages')
            ->delete()
            ->where('id', $_GET['id'], '=')
            ->execute();

        header("Location: /chats/messages?id=" . $message->chat_id);
    }

}
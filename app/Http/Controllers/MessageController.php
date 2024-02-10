<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use App\Core\Validations\ActiveStatus;
use App\Core\Validations\ImageType;
use App\Core\Validations\Max;
use App\Core\Validations\Validation;
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
            ->select(['messages.*', 'chats.name AS chat_name', 'chats.image AS chat_image', 'users.username AS username', 'users.status AS user_status'])
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
        if (!empty($_FILES['image']['tmp_name'])) {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
<<<<<<< HEAD
                'image'=> [new ImageType],
=======
                'image' => [new ImageType],
>>>>>>> 9e9b44a4b6cae7ac572cc09bdd86875512d486b9
                'status' => [new ActiveStatus]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        } else {

            $rules = [
                'body' => [new Max(100)],
                'status' => [new ActiveStatus]
            ];

            $request = array_intersect_key($_REQUEST, $rules);
        }

        if (Validation::make($request, $rules)) {
            //store-message

            if (!empty($_FILES['image']['tmp_name'])) {

                $image = (new ImageController)->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            } else {
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
            ->where('id', $_GET['id'], '=')
            ->execute()
            ->fetch();

        $_SESSION['editedMessage'] = $message;

        header("Location: /chats/messages?id=$message->chat_id");
    }

    public function update()
    {
        if (!empty($_FILES['image']['tmp_name'])) {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
                'image' => [new ImageType]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        } else {

            $rules = [
                'body' => [new Max(100)],
            ];

            $request = array_intersect_key($_REQUEST, $rules);
        }

        if (Validation::make($request, $rules)) {
            //update message

            if (!empty($_FILES['image']['tmp_name'])) {

                $image = (new ImageController)->store($_FILES['image']['tmp_name'], $_FILES['image']['name']);
            } else {
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

    public function ajaxStore()
    {
        $this->queryBuilder->table('messages')
            ->insert(['user_id', 'chat_id', 'body', 'image', 'created_at'])
            ->execute([
                'user_id' => $_REQUEST['user_id'],
                'chat_id' => $_REQUEST['chat_id'],
                'body' => $_REQUEST['body'],
                'image' => null,
                'created_at' => date('Y_m_d H:i:s', time())
            ]);

        $lastId = $this->queryBuilder
            ->table('messages')
            ->select(['id'])
            ->orderBy('created_at', 'DESC')
            ->execute()
            ->fetch()->id;

        $content = $this->getMessageTemplate($_REQUEST['body']);

//        header("Content-Type: application/json");
        echo json_encode(['content' => $content, 'id' => $lastId]);
    }

    public function lastMessage()
    {
        $id = $_REQUEST['id'];

        $lastMessage = $this->queryBuilder
            ->table('messages')
            ->select()
            ->orderBy('created_at', 'DESC')
            ->execute()
            ->fetch();

        $content = null;
        $newMessageExists = $id != $lastMessage->id;


        if ($newMessageExists) {
            $content = $this->getMessageTemplate($lastMessage->body);
        }

        echo json_encode([
            'newMessageExists' => $newMessageExists,
            'content' => $content,
            'id' => $lastMessage->id
        ]);
    }

    public function getMessageTemplate($body)
    {
        $html = '<div class="messages rightSide">';
        $html .= '<div class="user-message-operations w-15 d-none">';
        $html .= '<div class="list-group">';
        // content
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="message-body">';
        $html .= '<div class="d-flex justify-content-between">';
        $html .= '<h4>You</h4>';
        $html .= '</div>';
        $html .= '<p>' . $body . '</p>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

}
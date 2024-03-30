<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use App\Core\Validations\ActiveStatus;
use App\Core\Validations\ImageType;
use App\Core\Validations\Max;
use App\Core\Validations\Required;
use App\Core\Validations\Validation;
use App\Events\MessageHasBeenSeen;
use Morilog\Jalali\Jalalian;
use PDO;

date_default_timezone_set('Asia/Tehran');
class MessageController
{
    private MysqlQueryBuilder $queryBuilder;

    public function __construct()
    {
        global $queryBuilder;

        $this->queryBuilder = $queryBuilder;
    }

    public function index()
    {
        (new LoginController)->checkLogin();

        $messages = $this->queryBuilder->table('messages')
            ->select(['messages.*', 'users.username AS username', 'users.status AS user_status'])
            ->join('JOIN', 'users')
            ->on('messages.user_id', 'users.id')
            ->where('chat_id', $_GET['id'], '=')
            ->orderBy('created_at')
            ->execute()
            ->fetchAll();

        $chat = $this->queryBuilder->table('chats')
            ->select()
            ->where('id', $_GET['id'], '=')
            ->execute()
            ->fetch();

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
                'image'=> [new ImageType],
                'status' => [new ActiveStatus]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Required, new Max(100)],
                'status' => [new ActiveStatus]
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
                'image'=> [new ImageType],
                'status' => [new ActiveStatus]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Required, new Max(100)],
                'status' => [new ActiveStatus]
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

    public function ajaxStore()
    {
        if(!empty($_FILES['image']['tmp_name']))
        {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
                'image'=> [new ImageType],
                'status' => [new ActiveStatus]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Required, new Max(100)],
                'status' => [new ActiveStatus]
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

            $lastMessage = $this->queryBuilder->table('messages')
                ->select(['messages.*', 'users.username AS username', 'users.status AS user_status'])
                ->join('JOIN', 'users')
                ->on('messages.user_id', 'users.id')
                ->orderBy('created_at', 'DESC')
                ->execute()
                ->fetch();

            $content = $this->getMessageTemplate($lastMessage);

            echo json_encode(['message' => 'success' ,'content' => $content, 'id' => $lastMessage->id]);

        }else{

            echo json_encode(array_merge(['message' => 'fail'], ['errors' => (new Validation())->getErrors()]));
        }
    }

    public function lastMessage()
    {
        $id = $_REQUEST['id'];

        $lastMessage = $this->queryBuilder->table('messages')
            ->select(['messages.*', 'users.username AS username', 'users.status AS user_status'])
            ->join('JOIN', 'users')
            ->on('messages.user_id', 'users.id')
            ->where('chat_id', $_REQUEST['chat_id'] , '=')
            ->orderBy('created_at', 'DESC')
            ->execute()
            ->fetch();

        $newMessageExists = $id != $lastMessage->id;

        $content = null;

        if($newMessageExists)
        {
            $content = $this->getMessageTemplate($lastMessage);
        }

        echo json_encode([
                'newMessageExists' => $newMessageExists,
                'content' => $content,
                'id' => $lastMessage->id
        ]);
    }

    public function ajaxEdit()
    {
        $editedMessage = $this->queryBuilder->table('messages')
            ->select()
            ->where('id', $_REQUEST['id'], '=')
            ->execute()
            ->fetch();

        echo json_encode(['content' => $editedMessage]);
    }

    public function ajaxUpdate()
    {
        if(!empty($_FILES['image']['tmp_name']))
        {
            $image = ['image' => $_FILES['image']['tmp_name']];

            $rules = [
                'body' => [new Max(100)],
                'image'=> [new ImageType],
                'status' => [new ActiveStatus]
            ];

            $request = array_merge(array_intersect_key($_REQUEST, $rules), $image);


        }else {

            $rules = [
                'body' => [new Required, new Max(100)],
                'status' => [new ActiveStatus]
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
                ->where('id', $_REQUEST['id'], '=')
                ->execute([
                    'body' => $_REQUEST['body'],
                    'image' => $image,
                    'updated_at' => date('Y_m_d H:i:s', time())
                ]);


            $updatedMessage = $this->queryBuilder->table('messages')
            ->select()
            ->where('id', $_REQUEST['id'], '=')
            ->execute()
            ->fetch();

            echo json_encode(['message' => 'success' ,'content' => $updatedMessage]);

        }else{

            echo json_encode(array_merge(['message' => 'fail'], ['errors' => (new Validation())->getErrors()]));
        }
    }

    public function ajaxDestroy()
    {
        $this->queryBuilder->table('messages')
            ->delete()
            ->where('id', $_REQUEST['id'], '=')
            ->execute();

        echo json_encode(['id' => $_REQUEST['id']]);
    }

    public function getMessageTemplate($message)
    {
        $user = $_SESSION['user'];
        ob_start(); ?>
        <div class="messages <?= $user->id == $message->user_id ? 'rightSide' : 'leftSide' ?>">

            <?php if($user->id == $message->user_id):?>

                <div class="user-message-operations w-15 d-none">
                    <div class="list-group">
                        <a href="/chats/messages/edit?id=<?= $message->id ?>" class="list-group-item list-group-item-action">Edit Message</a>
                        <a href="/chats/messages/delete?id=<?= $message->id ?>" class="list-group-item list-group-item-action">Delete Message</a>
                    </div>
                </div>

            <?php endif; ?>

            <div class="message-body">
                <span class="d-none message-id-span"><?= $message->id ?></span>
                <div class="d-flex justify-content-between">
                    <h4><?= $message->user_id !== $user->id ? $message->username : 'You' ?></h4>
                    <?php if($message->user_status == 0): ?>
                        <i class="fa-solid fa-ban text-danger"></i>
                    <?php endif; ?>
                </div>

                <p><?= $message->body ?? '' ?></p>
                <?php if(!empty($message->image)): ?>
                    <img class="pb-3" src="<?= $message->image ?>">
                <?php endif; ?>
                <p class="message-date-and-seen">
                    <?= Jalalian::forge($message->created_at)->format('Y-m-d H:i') ?>

                    <?php
                    if($user->id === $message->user_id) {
                            ?>
                            <i class="seen-icon fa-solid fa-check"></i>
                            <?php
                        }
                    ?>
                </p>
            </div>
            <?php if($user->user_type == 1 && $user->id !== $message->user_id): ?>

                <div class="admin-operations w-15 d-none">
                    <div class="list-group">
                        <a href="/users/block?user_id=<?= $message->user_id ?>&chat_id=<?= $message->chat_id ?>" class="list-group-item list-group-item-action">Block User</a>
                        <a href="/users/delete?user_id=<?= $message->user_id ?>&chat_id=<?= $message->chat_id ?>" class="list-group-item list-group-item-action">Delete User</a>
                        <a href="/chats/messages/delete?id=<?= $message->id ?>" class="list-group-item list-group-item-action">Delete Message</a>
                    </div>
                </div>

            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajaxSeen()
    {
        $user_id = $_SESSION['user']->id;
        $chat_id = $_REQUEST['chat_id'];
        $last_seen_id = $_REQUEST['last_seen_id'];

        $unSeenMessages = $this->queryBuilder
            ->table('messages')
            ->select()
            ->where('chat_id', $chat_id, '=')
            ->where('seen', '0', '=')
            ->where('user_id', $user_id, '<>')
            ->execute()
            ->fetchAll();

        $userSeenMessages = $this->queryBuilder
            ->table('messages')
            ->select()
            ->where('seen', '1', '=')
            ->where('user_id', $user_id, '=')
            ->where('id', $last_seen_id, '>')
            ->execute()
            ->fetchAll();

        $unSeenMessagesId = array_map(function ($unSeenMessage) {

            return $unSeenMessage->id;

        },$unSeenMessages);

        $userSeenMessagesId = array_map(function ($userSeenMessage) {

            return $userSeenMessage->id;
        }, $userSeenMessages);

      if($unSeenMessagesId)
       {
        MessageHasBeenSeen::ajaxRun($unSeenMessagesId);

      }

       echo json_encode(['content' => $userSeenMessagesId]);

    }

}
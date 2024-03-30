<?php

use App\Core\Validations\Validation;
use App\Events\MessageHasBeenSeen;
use Morilog\Jalali\Jalalian;

//$errors = (new Validation)->getErrors();

$user = $_SESSION['user'];

$messages = @$_SESSION['messages'];

//$editedMessage = @$_SESSION['editedMessage'];

$lastId = 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Page</title>
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="../../../public/css/chat-styles.css">
</head>

<body>
<section>
    <div class="right">
        <header>
            <div class="imgBx">
                <img src="<?= $chat->image ?>" alt="chat_image">
            </div>
            <div class="title">
                <h2><?= $chat->name ?></h2>
            </div>
            <div class="headIconBx">
                <a href="/logout">
                    Logout
                </a>
                <a href="/chats">
                    Chat Groups
                </a>
            </div>
        </header>
        <div id="parent-box" class="chatBx">

            <?php
                if(!empty($messages)):
                    foreach ($messages as $message):
                        $lastId = $message->id;

                        if($user->id === $message->user_id && $message->seen === 1)

                        $userLastSeenMessageId = $message->id;
            ?>

            <div class="messages <?= $user->id == $message->user_id ? 'rightSide' : 'leftSide' ?>">

                <?php if($user->id == $message->user_id):?>

                <div class="user-message-operations w-15 d-none">
                    <div class="list-group">
<!--                        <a href="#" class="list-group-item list-group-item-action">Reply</a>-->
                        <a href="/chats/messages/edit?id=<?= $message->id ?>" class="list-group-item list-group-item-action edit-link">Edit Message</a>
                        <a href="/chats/messages/delete?id=<?= $message->id ?>" class="list-group-item list-group-item-action delete-link">Delete Message</a>
                    </div>
                </div>

                <?php endif; ?>

                <div class="message-body">
                    <span class="d-none message-id-span"><?= $lastId ?></span>
                    <div class="d-flex justify-content-between">
                        <h4><?= $message->user_id !== $user->id ? $message->username : 'You' ?></h4>
                        <?php if($message->user_status == 0): ?>
                        <i class="fa-solid fa-ban text-danger"></i>
                        <?php endif; ?>
                    </div>

                    <p class="p-body"><?= $message->body ?? '' ?></p>
                    <?php if(!empty($message->image)): ?>
                    <img class="pb-3" src="<?= $message->image ?>">
                    <?php endif; ?>
                     <p class="message-date-and-seen">
                         <?= Jalalian::forge($message->created_at)->format('Y-m-d H:i') ?>
                         <?php
                         if($user->id === $message->user_id) {
                             if($message->seen == 0){
                         ?>
                         <i class="seen-icon fa-solid fa-check"></i>
                                 <?php }else{ ?>
                         <i class="fa-solid fa-check-double"></i>
                         <?php
                             }
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
//                    MessageHasBeenSeen::run($message);
                    endforeach;
                endif;
            ?>

        </div>

        <input id="last_id" type="hidden" name="last_id" value="<?= $lastId ?>">
        <input id="last_seen_id" type="hidden" name="last_seen_id" value="<?= $userLastSeenMessageId ?? 0 ?>">

<!--        --><?php
//            if(!isset($editedMessage)) {
//        ?>

        <form id="message-store-form" action="/chats/messages/store" method="POST" enctype="multipart/form-data">
            <div class="inputBx">
                <div class="input">
                    <div class="search">
                        <input id="body" type="text" name="body" maxlength="100" placeholder="Type your message here...">
                    </div>

                    <div class="inputIcon element">
                        <i class="fa-solid fa-camera camera-icon"></i><span id="image-path" class="name"></span>
                        <input id="image" class="image-input" type="file" name="image">
                    </div>
                </div>

                <div>
                    <input id="user_id" type="hidden" name="user_id" value="<?= $user->id ?>">
                </div>

                <div>
                    <input id="chat_id" type="hidden" name="chat_id" value="<?= $chat->id ?>">
                </div>

                <div>
                    <input id="user_status" type="hidden" name="status" value="<?= $user->status ?>">
                </div>

                <button id="store-button" class="mic" type="submit">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

        <span id="body-error" class="validation-error"><?= $errors['body'] ?? '' ?></span>
        <span id="image-error" class="validation-error"><?= $errors['image'] ?? '' ?></span>
        <span id="status-error" class="validation-error"><?= $errors['status'] ?? '' ?></span>

<!--        --><?php
//            }else{
//        ?>
                <form class="d-none" id="message-update-form" action="" method="POST" enctype="multipart/form-data">
                    <div class="inputBx">
                        <div class="input">
                            <div class="search">
                                <div class="alert alert-warning edit-warning">
                                    <a href="/chats/messages?id=<?= $messages[0]->chat_id ?>" class="alert-link"><i class="fa-solid fa-xmark p-0 m-0 text-danger"></i></a>
                                    editing message
                                </div>
                                <input id="update-body" type="text" name="body" maxlength="100"
                                       value="">
                            </div>

                            <div class="inputIcon element">
                                <i class="fa-solid fa-camera camera-icon"></i><span id="update-image-path" class="name"></span>
                                <input class="image-input" type="file" name="image">
                            </div>
                        </div>

                        <div>
                            <input id="edited-message-id" type="hidden" name="id">
                        </div>

                        <div>
                            <input type="hidden" name="user_id" value="<?= $user->id ?>">
                        </div>

                        <div>
                            <input type="hidden" name="chat_id" value="<?= $messages[0]->chat_id ?>">
                        </div>

                        <div>
                            <input type="hidden" name="status" value="<?= $user->status ?>">
                        </div>

                        <button id="update-button" class="mic" type="submit">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <span id="update-body-error" class="validation-error"><?= $errors['body'] ?? '' ?></span>
                <span id="update-image-error" class="validation-error"><?= $errors['image'] ?? '' ?></span>
                <span id="update-status-error" class="validation-error"><?= $errors['status'] ?? '' ?></span>

<!--                --><?php //} ?>

    </div>
</section>

<script src="../../../public/js/jquery-3.7.1.min.js"></script>
<script src="../../../public/js/chat-index.js"></script>

</body>
</html>

<?php
//unset($_SESSION['errors'], $_SESSION['editedMessage'], $editedMessage);
?>
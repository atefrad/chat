<?php

use App\Core\Validations\Validation;
use App\Events\MessageHasBeenSeen;

$errors = (new Validation)->getErrors();

$user = $_SESSION['user'];

$messages = @$_SESSION['messages'];

$editedMessage = @$_SESSION['editedMessage'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../public/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="../../../public/css/chat-styles.css">
</head>

<body>
<section>
    <div class="right">
        <header>
            <div class="imgBx">
                <img src="<?= $messages[0]->chat_image ?>" alt="chat_image">
            </div>
            <div class="title">
                <h2><?= $messages[0]->chat_name ?></h2>
            </div>

        </header>
        <div class="chatBx">

            <?php
                if(!empty($messages)):
                    foreach ($messages as $message):
            ?>

            <div class="messages <?= $user->id == $message->user_id ? 'rightSide' : 'leftSide' ?>">

                <?php if($user->id == $message->user_id):?>

                <div class="user-message-operations w-15 d-none">
                    <div class="list-group">
<!--                        <a href="#" class="list-group-item list-group-item-action">Reply</a>-->
                        <a href="/chats/messages/edit?id=<?= $message->id ?>" class="list-group-item list-group-item-action">Edit Message</a>
                        <a href="/chats/messages/delete?id=<?= $message->id ?>" class="list-group-item list-group-item-action">Delete Message</a>
                    </div>
                </div>

                <?php endif; ?>

                <div class="message-body">
                    <div class="d-flex justify-content-between">
                        <h4><?= $message->user_id !== $user->id ? $message->username : 'You' ?></h4>
                        <?php if($message->user_status == 0): ?>
                        <i class="fa-solid fa-ban text-danger"></i>
                        <?php endif; ?>
                    </div>

                    <p><?= $message->body ?? '' ?></p>
                    <?php if(!empty($message->image)): ?>
                    <img src="<?= $message->image ?>">
                    <?php endif; ?>
                     <p class="message-date-and-seen">
                         <?= $message->created_at ?>

                         <?php
                         if($user->id === $message->user_id) {
                             if($message->seen == 0){
                         ?>
                         <i class="fa-solid fa-check"></i>
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
<!--                <p class="message-date"></p>-->
            </div>
            <?php
                    MessageHasBeenSeen::run($message);
                    endforeach;
                endif;
            ?>
        </div>

        <?php
            if(!isset($editedMessage)) {
        ?>

        <form action="/chats/messages/store" method="POST" enctype="multipart/form-data">
            <div class="inputBx">
                <div class="input">
                    <div class="search">
                        <input type="text" name="body" maxlength="100" placeholder="Type your message here...">
                    </div>

                    <div class="inputIcon element">
                        <i class="fa-solid fa-camera camera-icon"></i><span class="name"></span>
                        <input class="image-input" type="file" name="image">
                    </div>
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

                <button class="mic" type="submit">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

        <span class="validation-error"><?= $errors['body'] ?? '' ?></span>
        <span class="validation-error"><?= $errors['image'] ?? '' ?></span>
        <span class="validation-error"><?= $errors['status'] ?? '' ?></span>

        <?php
            }else{
        ?>
                <form action="/chats/messages/update?id=<?= $editedMessage->id ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputBx">
                        <div class="input">
                            <div class="search">
                                <div class="alert alert-warning edit-warning">
                                    <a href="/chats/messages?id=<?= $editedMessage->chat_id ?>" class="alert-link"><i class="fa-solid fa-xmark p-0 m-0 text-danger"></i></a>
                                    editing message
                                </div>
                                <input type="text" name="body" maxlength="100"
                                       value="<?= $editedMessage->body ?? '' ?>">
                            </div>



                            <div class="inputIcon element">
                                <i class="fa-solid fa-camera camera-icon"></i><span class="name"></span>
                                <input class="image-input" type="file" name="image">
                            </div>
                        </div>

                        <div>
                            <input type="hidden" name="user_id" value="<?= $user->id ?>">
                        </div>

                        <div>
                            <input type="hidden" name="chat_id" value="<?= $editedMessage->chat_id ?>">
                        </div>

                        <button class="mic" type="submit">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <span class="validation-error"><?= $errors['body'] ?? '' ?></span>
                <span class="validation-error"><?= $errors['image'] ?? '' ?></span>

                <?php } ?>


    </div>
</section>

<script src="../../../public/js/jquery-3.7.1.min.js"></script>
<script src="../../../public/js/chat-index.js"></script>
</body>
</html>

<?php
unset($_SESSION['errors'], $_SESSION['editedMessage'], $message);
?>
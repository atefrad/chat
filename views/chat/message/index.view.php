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
                        <a href="#" class="list-group-item list-group-item-action">reply</a>
                        <a href="/chats/messages/edit?id=<?= $message->id ?>" class="list-group-item list-group-item-action">edit</a>
                        <a href="/chats/messages/delete?id=<?= $message->id ?>" class="list-group-item list-group-item-action">delete</a>
                    </div>
                </div>

                <?php endif; ?>

                <div class="message-body">
                    <h4><?= $message->user_id !== $user->id ? $message->username : 'You' ?></h4>
                    <p><?= $message->body ?? '' ?></p>
                    <img src="<?= $message->image ?? '' ?>">
                     <p class="message-date-and-seen">
                         <?= $message->created_at ?>

                         <?php
                         if($user->id === $message->user_id) {
                         ?>
                         <i class="fa-solid fa-check"></i>
                         <i class="fa-solid fa-check-double"></i>
                         <?php
                         }
                         ?>
                     </p>
                </div>
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

                <button class="mic" type="submit">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

        <span class="validation-error"><?= $errors['body'] ?? '' ?></span>
        <span class="validation-error"><?= $errors['image'] ?? '' ?></span>

        <?php
            }else{
        ?>
                <form action="/chats/messages/update?id=<?= $editedMessage->id ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputBx">
                        <div class="input">
                            <div class="search">
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
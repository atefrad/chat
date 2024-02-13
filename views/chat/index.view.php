<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Groups</title>
    <link rel="stylesheet" href="../../public/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="../../public/css/chat-styles.css">
</head>

<body>
<section>
    <main>
        <div class="box group">
            <div class="title">
                <h2>group</h2>
            </div>


            <?php

            use Morilog\Jalali\Jalalian;

            if(!empty($chats)):
                    foreach($chats as $chat):
                        foreach($chatsLastMessage as $message)
                        {
                            if($message->chat_id == $chat->id)
                            {
                                $lastMessage = $message;
                            }
                        }
            ?>
            <a href="/chats/messages?id=<?= $chat->id?>">
                <img src="<?= $chat->image ?>" alt="theAvengers">
                <div class="nameBx">
                    <div class="name">
                        <h2><?= $chat->name ?></h2>
                        <h4><span><?= Jalalian::forge($lastMessage->created_at)->format('Y-m-d H:i') ?></span></h4>
                    </div>
                    <div class="mess">
                        <h3><?= substr($lastMessage->body, '0', '20') ?></h3>

                        <?php if(empty($lastMessage->body) && !empty($lastMessage->image)):?>

                             <h3> image </h3>

                        <?php endif; ?>

                    </div>
                </div>
            </a>
            <?php
                    endforeach;
                endif;
            ?>
        </div>
    </main>

</section>
</body>

</html>
<?php

use App\Core\Validations\Validation;

$errors = (new Validation)->getErrors();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../../public/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/login-register-styles.css">

</head>
<body>

<div class="container">
    <div class="screen height-600px">
        <div class="screen__content">
            <form action="/login" method="POST" class="login">
                <div class="login__field">
                    <i class="login__icon fa-solid fa-user"></i>
                    <input type="text" name="username" class="login__input" placeholder="Username">
                    <span class="validation-error"><?= $errors['username'] ?? '' ?></span>
                </div>
                <div class="login__field">
                    <i class="login__icon fa-solid fa-lock"></i>
                    <input type="password" name="password" class="login__input" placeholder="Password">
                    <span class="validation-error"><?= $errors['password'] ?? '' ?></span>
                </div>

                <div>
                    <span class="validation-error"><?= $errors['login'] ?? '' ?></span>
                </div>

                <button class="button login__submit">
                    <span class="button__text">Log In Now</span>
                    <i class="button__icon fa-solid fa-chevron-right"></i>
                </button>
            </form>

            <div class="register-link-div">
                <div class="register-link-container">
                <a href="/register-form" class="register-link">Register</a>
                </div>
            </div>

        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>


</body>
</html>

<?php
unset($_SESSION['errors']);
?>

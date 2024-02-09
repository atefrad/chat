<?php

use App\Core\Validations\Validation;

$errors = (new Validation)->getErrors();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../../public/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/login-register-styles.css">

</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form action="/register" method="POST" class="register">
                <div class="register__field">
                    <i class="register__icon fa-solid fa-user"></i>
                    <input type="text" name="username" class="login__input" placeholder="Username">
                    <span class="validation-error"><?= $errors['username'] ?? '' ?></span>
                </div>

                <div class="register__field">
                    <i class="register__icon fa-solid fa-envelope"></i>
                    <input type="email" name="email" class="login__input" placeholder="Email">
                    <span class="validation-error"><?= $errors['email'] ?? '' ?></span>
                </div>

                <div class="register__field">
                    <i class="register__icon fa-solid fa-circle-user"></i>
                    <input type="name" name="name" class="login__input" placeholder="Name">
                    <span class="validation-error"><?= $errors['name'] ?? '' ?></span>
                </div>

                <div class="register__field">
                    <i class="register__icon fa-solid fa-lock"></i>
                    <input type="password" name="password" class="login__input" placeholder="Password">
                    <span class="validation-error"><?= $errors['password'] ?? '' ?></span>
                </div>

                <div class="register__field">
                    <i class="register__icon fa-solid fa-lock"></i>
                    <input type="password" name="password_confirmation" class="login__input" placeholder="Confirm Password">
                    <span class="validation-error"><?= $errors['password_confirmation'] ?? '' ?></span>
                </div>

                <button class="button login__submit">
                    <span class="button__text">Register</span>
                    <i class="button__icon fa-solid fa-chevron-right"></i>
                </button>
            </form>

            <div class="login-link-div">
                <div class="login-link-container">
                    <a href="/login-form" class="login-link">Login</a>
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

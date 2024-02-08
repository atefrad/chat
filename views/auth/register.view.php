<!--<!doctype html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport"-->
<!--          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">-->
<!--    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">-->
<!--    <title>register</title>-->
<!--</head>-->
<!--<body>-->
<!--    <div class="container">-->
<!--        <h3 class="mt-4">Register form</h3>-->
<!--        <form action="" method="post">-->
<!--            <div class="my-3">-->
<!--                <label for="username" class="form-label">Username</label>-->
<!--                <input type="text" class="form-control" id="username" name="username" placeholder="username">-->
<!--            </div>-->
<!---->
<!--            <div class="my-3">-->
<!--                <label for="email" class="form-label">Email</label>-->
<!--                <input type="email" class="form-control" id="email" name="email" placeholder="email">-->
<!--            </div>-->
<!---->
<!--            <div class="my-3">-->
<!--                <label for="name" class="form-label">Name</label>-->
<!--                <input type="text" class="form-control" id="name" name="name" placeholder="name">-->
<!--            </div>-->
<!---->
<!--            <div class="my-3">-->
<!--                <label for="password" class="form-label">Password</label>-->
<!--                <input type="password" class="form-control" id="password" name="password" placeholder="password">-->
<!--            </div>-->
<!---->
<!--            <div class="my-3">-->
<!--                <input type="submit" class="btn btn-success" name="submit" value="register">-->
<!--            </div>-->
<!--        </form>-->
<!--    </div>-->
<!--</body>-->
<!--</html>-->

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
    <!--    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>-->
    <!--    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>-->
    <link rel="stylesheet" href="../../public/css/login-register-styles.css">

</head>
<body>
<!-- partial:index.partial.html -->
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
<!-- partial -->

</body>
</html>

<?php
unset($_SESSION['errors']);
?>
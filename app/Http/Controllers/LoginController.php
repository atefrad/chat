<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlQueryBuilder;
use App\Core\Validations\Required;
use App\Core\Validations\Validation;

class LoginController
{
    private MysqlQueryBuilder $queryBuilder;

    public function __construct()
    {
        global $queryBuilder;

        $this->queryBuilder = $queryBuilder;
    }

    public function create(): void
    {
        require_once './views/auth/login.view.php';

        unset($_SESSION['user']);
    }

    public function store(): void
    {
        if(Validation::make($_REQUEST, [
            'username' => [new Required],
            'password' => [new Required]
        ]))
        {
            $user = $this->queryBuilder->table('users')
                ->select()
                ->where('username', $_REQUEST['username'] , '=')
                ->where('password', $_REQUEST['password'], '=')
                ->execute()
                ->fetch();

            if($user)
            {
                $_SESSION['user'] = $user;
                setcookie('user_id', $user->id, time() + 3600);
                header("Location: /chats");

            }else{
                $_SESSION['errors']['login'] = 'The username or password is not correct!';
                header("Location: /login-form");
            }


        }else {
            header("Location: /login-form");
        }
    }

    public function checkLogin(): void
    {
        if(isset($_COOKIE['user_id']))
        {
            $_SESSION['user']  = $this->queryBuilder->table('users')
                ->select()
                ->where('id', $_COOKIE['user_id'] , '=')
                ->execute()
                ->fetch();
        }

        if(!isset($_SESSION['user']))
        {
            header("Location: /login-form");
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);

        setcookie('user_id', '', time() - 10);

        header("Location: /login-form");
    }
}
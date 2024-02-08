<?php

namespace App\Http\Controllers;

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use App\Core\Validations\Confirmed;
use App\Core\Validations\Email;
use App\Core\Validations\Max;
use App\Core\Validations\Min;
use App\Core\Validations\RegularExpression;
use App\Core\Validations\Required;
use App\Core\Validations\Unique;
use App\Core\Validations\Validation;
use PDO;

class RegisterController
{
    public function create(): void
    {
        require_once './views/auth/register.view.php';
    }

    public function store()
    {
        if(Validation::make($_REQUEST, [
            'username' => [new Required,new Unique, new Min(3), new Max(32), new RegularExpression("/^[a-zA-Z0-9_]+$/")],
            'email' => [new Required,new Unique, new Email],
            'name' => [new Required, new Min(3), new Max(32), new RegularExpression("/^[a-z\s]+$/")],
            'password' => [new Required, new Min(4), new Max(32)],
            'password_confirmation' => [new Required, new Confirmed],
        ]))
        {
            $connection = MysqlConnection::getInstance();

            $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));

            $queryBuilder = new MysqlQueryBuilder($connection);

            $queryBuilder->table('users')
                ->insert(['username', 'email', 'name', 'password'])
                ->execute([
                    'username' => $_REQUEST['username'],
                    'email' => $_REQUEST['email'],
                    'name' => $_REQUEST['name'],
                    'password' => $_REQUEST['password']
                ]);

            header("Location: /login-form");

        }else{
            header("Location: /register-form");
        }


    }

}
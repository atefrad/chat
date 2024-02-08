<?php

namespace App\Core\Validations;

use App\Core\Validations\Contracts\RuleInterface;
use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;
use PDO;

class Unique extends BaseRule implements RuleInterface
{

    public function validate(string $key, $value): bool
    {
        $connection = MysqlConnection::getInstance();

        $connection->setPDO(new PDO('mysql:host=localhost;dbname=chat;', 'root'));

        $queryBuilder = new MysqlQueryBuilder($connection);

        $user = $queryBuilder->table('users')
            ->select()
            ->where($key, $value, '=')
            ->execute()
            ->fetch();

        return !boolval($user);

    }

    public function message(string $key): string
    {
        return "The {$key} attribute must be unique!";
    }

}
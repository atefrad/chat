<?php

namespace App\Core\Database;

use App\Core\Database\Contracts\DatabaseConnectionInterface;
use PDO;

class MysqlConnection implements DatabaseConnectionInterface
{
    private static ?MysqlConnection $connection = null;

    private PDO $pdo;

    private function __construct()
    {

    }

    public static function getInstance(): self
    {
        self::$connection = is_null(self::$connection) ? new MysqlConnection() : self::$connection;

        return self::$connection;
    }

    public function setPDO(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

}
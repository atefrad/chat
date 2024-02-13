<?php

use App\Core\Database\MysqlConnection;
use App\Core\Database\MysqlQueryBuilder;

$connection = MysqlConnection::getInstance();

$connection->setPDO(new PDO("mysql:host=localhost;dbname=chat;", "root"));

$queryBuilder = new MysqlQueryBuilder($connection);
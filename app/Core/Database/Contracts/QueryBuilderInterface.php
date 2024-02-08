<?php

namespace App\Core\Database\Contracts;

interface QueryBuilderInterface
{
    public function table(string $table);

    public function insert(array $fields);

    public function update(array $fields);

    public function select(array $columns = ['*']);

    public function delete();

    public function where(string $column, string $value, string $operation);

    public function execute(array $values = []);

    public function fetchAll(): array;
}
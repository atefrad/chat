<?php

namespace App\Core\Database;

use App\Core\Database\Contracts\QueryBuilderInterface;
use PDO;
use PDOStatement;

class MysqlQueryBuilder implements QueryBuilderInterface
{

    private MysqlConnection $connection;

    private string $table;

    private string $query = '';

    private PDOStatement $statement;

    public function __construct(MysqlConnection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): MysqlConnection
    {
        return $this->connection;
    }

    public function table(string $table) : self
    {
        $this->query = '';

        $this->table = $table;

        return $this;
    }

    public function insert(array $fields) : self
    {
//        $valuesPlaceholders = '';
//
//        for($i = 0; $i < count($fields); $i++)
//        {
//            if($i == count($fields) - 1)
//            {
//                $valuesPlaceholders .= '?';
//                break;
//            }
//            $valuesPlaceholders .= '?,';
//        }

        $pattern = "INSERT INTO %s (%s) VALUES (%s)";

        $this->query .= sprintf($pattern, $this->table, implode(',' , $fields), (':' . implode(', :', $fields)));

//        $this->query .= sprintf($pattern, $this->table, implode(',' , $fields), $valuesPlaceholders);

        return $this;
   }

    public function update(array $fields) : self
    {
        //update bands set name = ?, song = ?;
        $pattern = "UPDATE %s SET %s";

        $this->query .= sprintf($pattern, $this->table, (implode('=?,', $fields) . ' = ? '));

        return $this;
    }

    public function select(array $columns = ['*']) : self
    {
        $pattern = 'SELECT %s FROM %s';

        $this->query .= sprintf($pattern, implode(',', $columns), $this->table);

        return $this;
    }

    public function execute(array $values = []) : self
    {
        $this->statement = $this->connection->getPDO()->prepare($this->query);

        $values = str_contains($this->query,'?', ) ? array_values($values) : $values;

        $this->statement->execute($values);

        return $this;
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch(): object| bool
    {
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    public function delete() : self
    {
        $pattern = 'DELETE FROM %s';

        $this->query .= sprintf($pattern, $this->table);

        return $this;
    }

    public function where(string $column, string $value, string $operation) : self
    {
        if(!str_contains($this->query, 'WHERE'))
        {
            $this->query .= ' WHERE';

        }else {
            $this->query .= ' AND';
        }

        if($operation == 'IN')
        {
            $pattern = " $column $operation $value";

        }else{

            $pattern = " $column $operation '$value'";
        }



        $this->query .= $pattern;

        return $this;
    }

    public function andWhere(string $column, string $value, string $operation) : self
    {
        $pattern = " AND $column $operation '$value'";

        $this->query .= $pattern;

        return $this;
    }

    public function orWhere(string $column, string $value, string $operation) : self
    {
        $pattern = " OR $column $operation '$value'";

        $this->query .= $pattern;

        return $this;
    }

    public function join(string $joinType,string $secondTable): self
    { //'select * from chats left join messages on chats.id = messages.chat_id'
        $pattern = " {$joinType} {$secondTable}";

        $this->query .= $pattern;

        return $this;

    }

    public function on(string $relatedColumnOne, string $relatedColumnTwo): self
    {
        $pattern = " ON {$relatedColumnOne} = {$relatedColumnTwo}";

        $this->query .= $pattern;

        return $this;
    }

    public function orderBy(string $column, string $sortingOrder = 'ASC'): self
    { //select * from chats order by id desc|asc

        $pattern = " ORDER BY {$column} {$sortingOrder}";

        $this->query .= $pattern;

        return $this;
    }

    public function limit(int $limit): self
    { //select * from chats limit 1

        $pattern = " LIMIT {$limit}";

        $this->query .= $pattern;

        return $this;
    }

    public function groupBy(string $column): self
    { //select * from messages group by chat_id

        $pattern = " GROUP BY {$column}";

        $this->query .= $pattern;

        return $this;
    }
}
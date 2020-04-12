<?php

declare(strict_types=1);

namespace App;

class Database
{

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \PDOStatement
     */
    private $statement;


    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function query(string $query, array $params = [])
    {
        if (empty($params)) {
            $this->statement = $this->pdo->query($query);
        } else {
            $this->statement = $this->pdo->prepare($query);
            $this->statement->execute($params);
        }
    }


    public function fetchColumn(): string
    {
        return $this->statement->fetchColumn();
    }


    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }


    public function fetchAllAsKeyPair()
    {
        return $this->statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }


    public function fetchAllAsGroupedKeyPair()
    {
        return $this->statement->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_KEY_PAIR);
    }


    public function fetchSingle(): array
    {
        $row = $this->statement->fetch();
        if ($row === false) {
            return [];
        }

        return $row;
    }


    public function lastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }
}

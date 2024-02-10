<?php

namespace App\Database;

class DatabaseConnection
{
    private \PDO $pdo;

    private static ?self $instance = null;

    private function __construct()
    {
        $database = getenv('DB_NAME');
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $dsn = "mysql:dbname={$database};host={$host}";
        $this->pdo = new \PDO($dsn, $user, $password);
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @param string $sql
     * @return array<mixed>|false
     */
    public function select(string $sql): array|false
    {
        $sth = $this->pdo->query($sql);
        return $sth->fetchAll();
    }

    public function exec(string $sql): bool|int
    {
        return $this->pdo->exec($sql);
    }

    //TODO: change bool into false in 8.2
    public function lastInsertId(): bool|string
    {
        return $this->pdo->lastInsertId();
    }
}
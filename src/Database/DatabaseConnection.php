<?php

namespace App\Database;

use App\Database\Exceptions\DatabaseException;

class DatabaseConnection implements DatabaseConnectionInterface
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
     * @param array<string, float|bool|int|string|null> $values
     * @param array<ParameterTypes> $types
     * @return array<string,float|bool|int|string|null>
     */
    public function select(string $sql, array $values = [], array $types = []): array
    {
        $sth = $this->bindValues($sql, $values, $types);
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $values = [], array $types = []): bool|int
    {
        $sth = $this->bindValues($sql, $values, $types);
        return $sth->execute();
    }

    public function lastInsertId(): bool|int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @param string $sql
     * @param array $values
     * @param array<ParameterTypes> $types
     * @return false|\PDOStatement
     */
    private function bindValues(string $sql, array $values, array $types): \PDOStatement|false
    {
        $sth = $this->pdo->prepare($sql);
        foreach ($values as $name => $value) {
            $type = $types[$name]->toPDO();
            $success = $sth->bindValue(
                $value,
                $name,
                $type
            );
            if ($success) {
                continue;
            }
            throw new DatabaseException("{$value} of type {$type} for {$name} is incorrect");
        }
        return $sth;
    }
}
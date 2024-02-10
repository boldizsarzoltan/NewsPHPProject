<?php

namespace App\Database;

use App\Database\Exceptions\DatabaseException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    private \PDO $pdo;


    public function __construct()
    {
        $database = getenv('DB_NAME');
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $dsn = "mysql:dbname={$database};host={$host}";
        $this->pdo = new \PDO($dsn, $user, $password);
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
        $sth->execute($values);
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

    public function startTransaction(): bool|int
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): bool|int
    {
        $this->pdo->commit();
    }

    public function rollback(): bool|int
    {
        $this->pdo->rollBack();
    }


}
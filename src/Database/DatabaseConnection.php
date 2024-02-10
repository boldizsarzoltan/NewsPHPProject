<?php

namespace App\Database;

use App\Database\Exceptions\DatabaseException;
use App\Database\Exceptions\UnexpectedDatabaseFailure;
use App\Database\Exceptions\UnexpectedTransactionFailure;

class DatabaseConnection implements DatabaseConnectionInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $database = (string) getenv('DB_NAME');
        $host = (string) getenv('DB_HOST');
        $user = (string) getenv('DB_USER');
        $password = (string) getenv('DB_PASSWORD');
        $dsn = "mysql:dbname={$database};host={$host}";
        $this->pdo = new \PDO($dsn, $user, $password);
    }

    /**
     * @inheritDoc
     */
    public function select(string $sql, array $values = [], array $types = []): array
    {
        $sth = $this->bindValues($sql, $values, $types);
        if (false === $sth) {
            throw new UnexpectedDatabaseFailure();
        }
        $sth->execute($values);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @inheritDoc
     */
    public function execute(string $sql, array $values = [], array $types = []): bool|int
    {
        $sth = $this->bindValues($sql, $values, $types);
        if (false === $sth) {
            throw new UnexpectedDatabaseFailure();
        }
        return $sth->execute();
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param  string $sql
     * @param  array<string, float|bool|int|string|null> $values
     * @param  array<ParameterTypes> $types
     * @return false|\PDOStatement
     */
    private function bindValues(string $sql, array $values, array $types): \PDOStatement|false
    {
        $sth = $this->pdo->prepare($sql);
        foreach ($values as $name => $value) {
            $type = $types[$name]->toPDO();
            $success = $sth->bindValue(
                $name,
                $value,
                $type
            );
            if ($success) {
                continue;
            }
            throw new DatabaseException("{$value} of type {$type} for {$name} is incorrect");
        }
        return $sth;
    }

    public function startTransaction(): void
    {
        if (!$this->pdo->beginTransaction()) {
            throw new UnexpectedTransactionFailure();
        }
    }

    public function commit(): void
    {
        $this->pdo->commit();
        if (!$this->pdo->commit()) {
            throw new UnexpectedTransactionFailure();
        }
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
        if (!$this->pdo->rollBack()) {
            throw new UnexpectedTransactionFailure();
        }
    }
}

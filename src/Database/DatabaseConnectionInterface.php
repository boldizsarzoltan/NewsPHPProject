<?php

namespace App\Database;

interface DatabaseConnectionInterface
{
    /**
     * @param string $sql
     * @param array<string, float|bool|int|string|null> $values=[]
     * @param array<ParameterTypes> $types=[]
     * @return array<string,float|bool|int|string|null>
     */
    public function select(string $sql, array $values = [], array $types = []): array;

    /**
     * @param string $sql
     * @param array<string, float|bool|int|string|null> $values=[]
     * @param array<ParameterTypes> $types=[]
     * @return bool|int
     */
    public function execute(string $sql, array $values=[], array $types=[]): bool|int;

    public function lastInsertId(): bool|int;
}
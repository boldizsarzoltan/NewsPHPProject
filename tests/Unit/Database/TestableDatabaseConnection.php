<?php

namespace App\Tests\Unit\Database;

use App\Database\DatabaseConnection;

class TestableDatabaseConnection extends DatabaseConnection
{
    public string $dsn;
    public string $user;
    public string $password;

    public function __construct(
        private readonly \PDO $pdo
    ) {
        parent::__construct();
    }

    protected function getPdo(string $dsn, string $user, string $password) : \PDO{
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        return $this->pdo;
    }
}
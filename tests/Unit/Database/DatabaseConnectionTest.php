<?php

namespace App\Tests\Unit\Database;

use App\Database\Exceptions\DatabaseException;
use App\Database\Exceptions\MissingDatabaseTypeException;
use App\Database\Exceptions\UnexpectedDatabaseFailure;
use App\Database\Exceptions\UnexpectedTransactionFailure;
use App\Database\ParameterTypes;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private string $database;
    private string $host;
    private string $user;
    private string $password;
    private TestableDatabaseConnection $instance;
    /**
     * @var (object&\PHPUnit\Framework\MockObject\MockObject)|\PDO|(\PDO&object&\PHPUnit\Framework\MockObject\MockObject)|(\PDO&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    private \PDO|\PHPUnit\Framework\MockObject\MockObject $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = "database";
        putenv("DB_NAME={$this->database}");
        $this->host = "host";
        putenv("DB_HOST={$this->host}");
        $this->user = "user";
        putenv("DB_USER={$this->user}");
        $this->password = "password";
        putenv("DB_PASSWORD={$this->password}");
        $this->pdo = $this->createMock(\PDO::class);
        $this->instance = new TestableDatabaseConnection($this->pdo);
    }

    public function testPDO()
    {
        $this->assertEquals("mysql:dbname={$this->database};host={$this->host}", $this->instance->dsn);
        $this->assertEquals($this->password, $this->instance->password);
        $this->assertEquals($this->user, $this->instance->user);
    }

    public function testStartTransactionIfFails()
    {
        $this->expectException(UnexpectedTransactionFailure::class);
        $this->pdo
            ->expects(self::once())
            ->method("beginTransaction")
            ->willReturn(false);
        $this->instance->startTransaction();
    }

    public function testStartTransactionIfDoesntFail()
    {
        $this->pdo
            ->expects(self::once())
            ->method("beginTransaction")
            ->willReturn(true);
        $this->instance->startTransaction();
    }

    public function testStartIfFails()
    {
        $this->expectException(UnexpectedTransactionFailure::class);
        $this->pdo
            ->expects(self::once())
            ->method("commit")
            ->willReturn(false);
        $this->instance->commit();
    }

    public function testCommitIfDoesntFail()
    {
        $this->pdo
            ->expects(self::once())
            ->method("commit")
            ->willReturn(true);
        $this->instance->commit();
    }

    public function testRollbackIfFails()
    {
        $this->expectException(UnexpectedTransactionFailure::class);
        $this->pdo
            ->expects(self::once())
            ->method("rollBack")
            ->willReturn(false);
        $this->instance->rollback();
    }

    public function testRollbackIfDoesntFail()
    {
        $this->pdo
            ->expects(self::once())
            ->method("rollBack")
            ->willReturn(true);
        $this->instance->rollback();
    }

    public function testSelectThrowsExceptionIfPrepareFails()
    {
        $this->expectException(UnexpectedDatabaseFailure::class);
        $sql = "sql";
        $values = ["name" => "val"];
        $types = ["name" => ParameterTypes::TYPE_STRING];
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn(false);
        $this->instance->select($sql, $values, $types);
    }

    public function testSelectThrowsExceptionIfTypesAreMissing()
    {
        $this->expectException(MissingDatabaseTypeException::class);
        $sql = "sql";
        $values = ["name" => "val"];
        $types = [];
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($this->createMock(PDOStatement::class));
        $this->instance->select($sql, $values, $types);
    }

    public function testSelectThrowsExceptionIfBindFails()
    {
        $this->expectException(DatabaseException::class);
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(false);
        $this->instance->select($sql, $values, $types);
    }

    public function testSelectThrowsExceptionIfExecuteFails()
    {
        $this->expectException(UnexpectedDatabaseFailure::class);
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(true);
        $statement
            ->expects(self::once())
            ->method("execute")
            ->willReturn(false);
        $this->instance->select($sql, $values, $types);
    }

    public function testSelectWorksCorrectly()
    {
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $finalResult = ["finalResult"];
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(true);
        $statement
            ->expects(self::once())
            ->method("execute")
            ->willReturn(true);
        $statement
            ->expects(self::once())
            ->method("fetchAll")
            ->with(\PDO::FETCH_ASSOC)
            ->willReturn($finalResult);
        $this->assertEquals(
            $finalResult,
            $this->instance->select($sql, $values, $types)
        );
    }

    public function testExecuteThrowsExceptionIfPrepareFails()
    {
        $this->expectException(UnexpectedDatabaseFailure::class);
        $sql = "sql";
        $values = ["name" => "val"];
        $types = ["name" => ParameterTypes::TYPE_STRING];
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn(false);
        $this->instance->execute($sql, $values, $types);
    }

    public function testExecuteThrowsExceptionIfTypesAreMissing()
    {
        $this->expectException(MissingDatabaseTypeException::class);
        $sql = "sql";
        $values = ["name" => "val"];
        $types = [];
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($this->createMock(PDOStatement::class));
        $this->instance->execute($sql, $values, $types);
    }

    public function testExecuteThrowsExceptionIfBindFails()
    {
        $this->expectException(DatabaseException::class);
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(false);
        $this->instance->execute($sql, $values, $types);
    }

    public function testExecuteWorksIfExecuteFails()
    {
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(true);
        $statement
            ->expects(self::once())
            ->method("execute")
            ->willReturn(false);
       $this->assertFalse($this->instance->execute($sql, $values, $types));
    }

    public function testExecuteWorksIfExecuteDoesntFail()
    {
        $sql = "sql";
        $name = "name";
        $val = "val";
        $values = [$name => $val];
        $type = ParameterTypes::TYPE_STRING;
        $types = [$name => $type];
        $statement = $this->createMock(PDOStatement::class);
        $this->pdo
            ->expects(self::once())
            ->method("prepare")
            ->willReturn($statement);
        $statement
            ->expects(self::once())
            ->method("bindValue")
            ->with($name, $val, $type->toPDO())
            ->willReturn(true);
        $statement
            ->expects(self::once())
            ->method("execute")
            ->willReturn(true);
        $this->assertTrue($this->instance->execute($sql, $values, $types));
    }
}
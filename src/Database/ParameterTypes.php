<?php

namespace App\Database;

//types that represent the types accepted in Database
use App\Database\Exceptions\UnexpectedTypeException;

enum ParameterTypes: string
{
    case TYPE_STRING = "string";
    case TYPE_INT = "integer";
    case TYPE_FLOAT = "float";
    case TYPE_NULL = "null";
    case TYPE_BOOL = "bool";

    public function toPDO(): int
    {
        return match ($this->value) {
            self::TYPE_STRING, self::TYPE_FLOAT => \PDO::PARAM_STR,
            self::TYPE_INT => \PDO::PARAM_INT,
            self::TYPE_NULL => \PDO::PARAM_NULL,
            self::TYPE_BOOL => \PDO::PARAM_BOOL,
            default => throw new UnexpectedTypeException('Unexpected match value')
        };
    }
}
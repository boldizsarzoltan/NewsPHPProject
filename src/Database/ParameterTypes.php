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
        return match (ParameterTypes::tryFrom($this->value)) {
            default => throw new UnexpectedTypeException("Unexpected match value '{$this->value}'"),
            ParameterTypes::TYPE_STRING, ParameterTypes::TYPE_FLOAT => \PDO::PARAM_STR,
            ParameterTypes::TYPE_INT => \PDO::PARAM_INT,
            ParameterTypes::TYPE_NULL => \PDO::PARAM_NULL,
            ParameterTypes::TYPE_BOOL => \PDO::PARAM_BOOL,
        };
    }
}
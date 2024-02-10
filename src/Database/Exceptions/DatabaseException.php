<?php

/**
 * Generic database class exception, if the error is specific it should be declared separaterly
 */

namespace App\Database\Exceptions;

/**
 * Used in ConnectionInterface/Implementation
 */
class DatabaseException extends \RuntimeException
{
}

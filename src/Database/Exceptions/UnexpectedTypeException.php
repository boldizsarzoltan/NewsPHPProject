<?php

/**
 * Used in prepared statement parameter gets an unexpected type
 */

namespace App\Database\Exceptions;

/**
 * should only be thrown from ParameterTypes
 */
class UnexpectedTypeException extends \RuntimeException
{
}

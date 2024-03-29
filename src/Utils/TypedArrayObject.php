<?php

/**
 * Generic class to not repeat code
 */

namespace App\Utils;

use App\Utils\Exceptions\InvalidTypeException;

abstract class TypedArrayObject extends \ArrayObject
{
    abstract public function getType(): string;

    public function __construct(object|array $array = [], int $flags = 0, string $iteratorClass = "ArrayIterator")
    {
        foreach ($array as $item) {
            $this->verifyType($item);
        }
        parent::__construct($array, $flags, $iteratorClass);
    }

    public function append(mixed $value): void
    {
        $this->verifyType($value);
        parent::append($value); // TODO: Change the autogenerated stub
    }

    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->verifyType($value);
        parent::offsetSet($key, $value); // TODO: Change the autogenerated stub
    }

    public function exchangeArray(object|array $array): array
    {
        foreach ($array as $item) {
            $this->verifyType($item);
        }
        return parent::exchangeArray($array); // TODO: Change the autogenerated stub
    }

    private function verifyType(object $object): void
    {
        if ($object::class !== $this->getType()) {
            throw new InvalidTypeException();
        }
    }
}

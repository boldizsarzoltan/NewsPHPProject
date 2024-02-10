<?php

namespace App\Entity;

use App\Utils\TypedArrayObject;

class MultipleNews extends TypedArrayObject
{
    public function getType(): string
    {
        return News::class;
    }
}

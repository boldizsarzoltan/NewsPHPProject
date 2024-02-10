<?php

namespace App\Entity;

use App\Utils\TypedArrayObject;

class Comments extends TypedArrayObject
{
    public function getType(): string
    {
        return Comment::class;
    }
}

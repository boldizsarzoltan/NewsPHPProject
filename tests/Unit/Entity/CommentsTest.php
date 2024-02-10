<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\Comments;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    public function testType()
    {
        $this->assertEquals((new Comments())->getType(), Comment::class);
    }
}
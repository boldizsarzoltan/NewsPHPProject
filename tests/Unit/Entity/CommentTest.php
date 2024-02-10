<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Comment;
use App\Entity\Exception\InvalidEntityException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private int $newsId;
    private string $body;
    private DateTimeImmutable $createdAt;
    private int $id;
    private Comment $instance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsId = 1;
        $this->body = "body";
        $this->createdAt = new \DateTimeImmutable("2024-02-11");
        $this->id = 2;
        $this->instance = new Comment(
            $this->newsId,
            $this->body,
            $this->createdAt,
            $this->id
        );
    }

    public function testWorksCorrectly()
    {
        $this->assertEquals($this->newsId, $this->instance->getNewsId());
        $this->assertEquals($this->body, $this->instance->getBody());
        $this->assertEquals($this->createdAt, $this->instance->getCreatedAt());
        $this->assertEquals($this->id, $this->instance->getId());
    }

    public function testThrowsExceptionIfUninitilizaedIdGiven()
    {
        $this->expectException(InvalidEntityException::class);
        $this->instance = new Comment(
            $this->newsId,
            $this->body,
            $this->createdAt,
        );
        $this->instance->getId();
    }

}
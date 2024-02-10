<?php

namespace App\Repositories\Builder;

use App\Entity\Comment;
use App\Repositories\Exceptions\InvalidCommentExceception;

class CommentBuilder
{
    private ?int $newsId;
    private ?string $body;
    private ?string $createdAt;
    private ?int $id;

    public function setNewsId(int $newsId): CommentBuilder
    {
        $this->newsId = $newsId;
        return $this;
    }

    public function setBody(string $body): CommentBuilder
    {
        $this->body = $body;
        return $this;
    }

    public function setCreatedAt(string $createdAt): CommentBuilder
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setId(int $id): CommentBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function buildNew(): Comment
    {
        if(
            !isset($this->body) ||
            !isset($this->newsId) ||
            !isset($this->createdAt)
        ) {
            throw new InvalidCommentExceception();
        }

        return new Comment(
            $this->newsId,
            $this->body,
            new \DateTimeImmutable()
        );
    }

    public function buildExisting(): Comment
    {
        if(
            !isset($this->body) ||
            !isset($this->id) ||
            !isset($this->newsId) ||
            !isset($this->createdAt)
        ) {
            throw new InvalidCommentExceception();
        }

        return new Comment(
            $this->newsId,
            $this->body,
            new \DateTimeImmutable($this->createdAt),
            $this->id
        );
    }
}
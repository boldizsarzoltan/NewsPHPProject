<?php

namespace App\Entity;

class Comment
{
    public function __construct(
        protected int $newsId,
        protected string $body,
        protected \DateTimeInterface $createdAt,
        protected ?int $id = null
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getNewsId(): ?int
    {
        return $this->newsId;
    }
}

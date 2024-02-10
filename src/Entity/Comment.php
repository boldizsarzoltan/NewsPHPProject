<?php

namespace App\Entity;

use App\Entity\Exception\InvalidEntityException;

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
        if (is_null($this->id)) {
            throw new InvalidEntityException();
        }
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

<?php

namespace App\Entity;

use App\Entity\Exception\InvalidEntityException;

class News
{
    protected ?int $id;
    protected string $title;
    protected string $body;
    protected \DateTimeInterface $createdAt;

    public function __construct(
        string $title,
        string $body,
        \DateTimeInterface $createdAt,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = $createdAt;
    }


    public function getId(): int
    {
        if (is_null($this->id)) {
            throw new InvalidEntityException();
        }
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}

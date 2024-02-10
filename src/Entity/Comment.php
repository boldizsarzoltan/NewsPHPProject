<?php

namespace App\Entity;

class Comment
{
    protected int $id;
    protected string $body;
    protected \DateTimeInterface $createdAt;
    protected ?int $newsId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Comment
    {
        $this->id = $id;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): Comment
    {
        $this->body = $body;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): Comment
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getNewsId(): ?int
    {
        return $this->newsId;
    }

    public function setNewsId(int $newsId): Comment
    {
        $this->newsId = $newsId;
        return $this;
    }
}
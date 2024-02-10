<?php

namespace App\Entities;

class News
{
    protected int $id;
    protected string $title;
    protected string $body;
    protected \DateTimeInterface $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): News
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): News
    {
        $this->title = $title;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): News
    {
        $this->body = $body;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): News
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
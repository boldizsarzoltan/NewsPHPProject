<?php

namespace App\Repositories\Builder;

use App\Entity\News;
use App\Repositories\Exceptions\InvalidNewsExceception;

class NewsBuilder
{
    private ?int $id;
    private ?string $title;
    private ?string $body;
    private ?string $createdAt;

    public function setId(?int $id): NewsBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle(?string $title): NewsBuilder
    {
        $this->title = $title;
        return $this;
    }

    public function setBody(?string $body): NewsBuilder
    {
        $this->body = $body;
        return $this;
    }

    public function setCreatedAt(?string $createdAt): NewsBuilder
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function buildNew(): News
    {
        if (
            !isset($this->body)
            || !isset($this->title)
        ) {
            throw new InvalidNewsExceception();
        }
        return new News(
            $this->title,
            $this->body,
            new \DateTimeImmutable()
        );
    }

    /**
     * @return News
     * @throws InvalidNewsExceception
     */
    public function buildExisting(): News
    {
        if (
            !isset($this->body)
            || !isset($this->title)
            || !isset($this->createdAt)
            || !isset($this->id)
        ) {
            throw new InvalidNewsExceception();
        }
        return new News(
            $this->title,
            $this->body,
            new \DateTimeImmutable($this->createdAt),
            $this->id
        );
    }
}

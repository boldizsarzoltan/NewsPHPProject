<?php

namespace App\Repositories;

use App\Database\DatabaseConnection;
use App\Database\DatabaseConnectionInterface;
use App\Database\ParameterTypes;
use App\Entity\Comment;

class CommentRepository
{
    private DatabaseConnectionInterface $databaseConnection;

    public function __construct(DatabaseConnectionInterface $databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    public function listComments()
    {
        $rows = $this->databaseConnection->select('SELECT * FROM `comment`');

        $comments = [];
        foreach ($rows as $row) {
            $n = new Comment();
            $comments[] = $n->setId($row['id'])
                ->setBody($row['body'])
                ->setCreatedAt(new \DateTimeImmutable($row['created_at']))
                ->setNewsId($row['news_id']);
        }

        return $comments;
    }

    public function addCommentForNews(string $body, int $newsId): bool|string
    {
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
        $currentDateTime = new \DateTimeImmutable();
        $this->databaseConnection->execute(
            $sql,
            [
                $body,
                $currentDateTime->format("Y-m-d H:i:s"),
                $newsId
            ],
            [
                "body" => ParameterTypes::TYPE_STRING,
                "created_at" => ParameterTypes::TYPE_STRING,
                "news_id" => ParameterTypes::TYPE_INT
            ]
        );
        return $this->databaseConnection->lastInsertId();
    }

    public function deleteComment(int $id): bool|int
    {
        $sql = "DELETE FROM `comment` WHERE `id`= :id";
        return $this->databaseConnection->execute(
            $sql,
            ["id" => $id],
            ["id" => ParameterTypes::TYPE_INT]
        );
    }

    public function deleteByNewsId(int $newsId)
    {
        $sql = "DELETE FROM `comment` WHERE `news_id`= :news_id";
        return $this->databaseConnection->execute(
            $sql,
            ["news_id" => $newsId],
            ["news_id" => ParameterTypes::TYPE_INT]
        );
    }
}
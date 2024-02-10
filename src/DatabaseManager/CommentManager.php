<?php

namespace App\DatabaseManager;

use App\Database\DatabaseConnection;
use App\Database\ParameterTypes;
use App\Entities\Comment;

class CommentManager
{
    private static ?self $instance = null;

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function listComments()
    {
        $db = DatabaseConnection::getInstance();
        $rows = $db->select('SELECT * FROM `comment`');

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
        $db = DatabaseConnection::getInstance();
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
        $currentDateTime = new \DateTimeImmutable();
        $db->execute(
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
        return $db->lastInsertId();
    }

    public function deleteComment(int $id): bool|int
    {
        $db = DatabaseConnection::getInstance();
        $sql = "DELETE FROM `comment` WHERE `id`= :id";
        return $db->execute(
            $sql,
            ["id" => $id],
            ["" => ParameterTypes::TYPE_INT]
        );
    }
}
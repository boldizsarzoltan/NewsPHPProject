<?php

namespace App\DatabaseManager;

use App\Database\DatabaseConnection;
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
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES('" . $body . "','" . date('Y-m-d') . "','" . $newsId . "')";
        $db->exec($sql);
        return $db->lastInsertId($sql);
    }

    public function deleteComment(int $id): bool|int
    {
        $db = DatabaseConnection::getInstance();
        $sql = "DELETE FROM `comment` WHERE `id`=" . $id;
        return $db->exec($sql);
    }
}
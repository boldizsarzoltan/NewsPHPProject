<?php

namespace App\DatabaseManager;

use App\Database\DatabaseConnection;
use App\Database\ParameterTypes;
use App\Entities\News;

final class NewsManager
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return array<News>
     * list all news
     */
    public function listNews(): array
    {
        $db = DatabaseConnection::getInstance();
        $rows = $db->select('SELECT * FROM `news`');
        $news = [];
        foreach ($rows as $row) {
            $n = new News();
            $news[] = $n->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt(new \DateTimeImmutable($row['created_at']));
        }

        return $news;
    }

    /**
     * add a record in news table
     */
    public function addNews(string $title, string $body): int|bool
    {
        $db = DatabaseConnection::getInstance();
        $currentDateTime = new \DateTimeImmutable();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(':title',':body',':created_at')";
        $db->execute(
            $sql,
            [
                "title" => $title,
                "body" => $body,
                "created_at" => $currentDateTime->format("Y-m-d H:i:s")
            ],
            [

                "title" => ParameterTypes::TYPE_STRING,
                "body" => ParameterTypes::TYPE_STRING,
                "created_at" => ParameterTypes::TYPE_STRING
            ]
        );
        return $db->lastInsertId();
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews(int $id): int|bool
    {
        $comments = CommentManager::getInstance()->listComments();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        foreach ($idsToDelete as $id) {
            CommentManager::getInstance()->deleteComment($id);
        }

        $db = DatabaseConnection::getInstance();
        $sql = "DELETE FROM `news` WHERE `id`= :id";
        return $db->execute(
            $sql,
            ["id" => $id],
            ["id" => ParameterTypes::TYPE_INT]
        );
    }
}
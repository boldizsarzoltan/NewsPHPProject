<?php

namespace App\DatabaseManager;

use App\Database\DatabaseConnection;
use App\Entities\News;

final class NewsManager
{
    private static $instance = null;

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
     * list all news
     */
    public function listNews()
    {
        $db = DatabaseConnection::getInstance();
        $rows = $db->select('SELECT * FROM `news`');

        $news = [];
        foreach ($rows as $row) {
            $n = new News();
            $news[] = $n->setId($row['id'])
                ->setTitle($row['title'])
                ->setBody($row['body'])
                ->setCreatedAt($row['created_at']);
        }

        return $news;
    }

    /**
     * add a record in news table
     */
    public function addNews($title, $body)
    {
        $db = DatabaseConnection::getInstance();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES('" . $title . "','" . $body . "','" . date('Y-m-d') . "')";
        $db->exec($sql);
        return $db->lastInsertId($sql);
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews($id)
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
        $sql = "DELETE FROM `news` WHERE `id`=" . $id;
        return $db->exec($sql);
    }
}
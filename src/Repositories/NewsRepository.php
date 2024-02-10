<?php

namespace App\Repositories;

use App\Database\DatabaseConnection;
use App\Database\DatabaseConnectionInterface;
use App\Database\ParameterTypes;
use App\Entities\News;

final class NewsRepository
{
    private static ?self $instance = null;

    private function __construct(
        private readonly DatabaseConnectionInterface $databaseConnection,
        private readonly CommentRepository $commentManager
    ) {
    }

    public static function getInstance(
        DatabaseConnectionInterface $databaseConnection,
        CommentRepository $commentManager
    ) {
        if (null === self::$instance) {
            self::$instance = new self($databaseConnection, $commentManager);
        }
        return self::$instance;
    }

    /**
     * @return array<News>
     * list all news
     */
    public function listNews(): array
    {
        $rows = $this->databaseConnection->select('SELECT * FROM `news`');
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
        $currentDateTime = new \DateTimeImmutable();
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(':title',':body',':created_at')";
        $this->databaseConnection->execute(
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
        return $this->databaseConnection->lastInsertId();
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews(int $id): int|bool
    {
        $comments = $this->commentManager->listComments();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        foreach ($idsToDelete as $id) {
            $this->commentManager->deleteComment($id);
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
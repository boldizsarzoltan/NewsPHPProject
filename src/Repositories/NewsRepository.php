<?php

namespace App\Repositories;

use App\Database\DatabaseConnection;
use App\Database\DatabaseConnectionInterface;
use App\Database\ParameterTypes;
use App\Entity\News;

final class NewsRepository
{
    private DatabaseConnectionInterface $databaseConnection;
    private CommentRepository $commentManager;

    public function __construct(
        DatabaseConnectionInterface $databaseConnection,
        CommentRepository $commentManager
    ) {
        $this->databaseConnection = $databaseConnection;
        $this->commentManager = $commentManager;
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
            $news[] = $this->createNews($row);
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
        $comments = $this->commentManager->deleteByNewsId($id);

        $sql = "DELETE FROM `news` WHERE `id`= :id";
        return $this->databaseConnection->execute(
            $sql,
            ["id" => $id],
            ["id" => ParameterTypes::TYPE_INT]
        );
    }

    public function createNews(array $row): News
    {
        return new News(
            $row['title'],
            $row['body'],
            new \DateTimeImmutable($row['created_at']),
            $row['id']
        );
    }
}
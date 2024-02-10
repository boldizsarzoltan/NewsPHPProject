<?php

namespace App\Repositories;

use App\Database\DatabaseConnectionInterface;
use App\Database\ParameterTypes;
use App\Entity\MultipleNews;
use App\Entity\News;
use App\Repositories\Builder\NewsBuilder;
use App\Repositories\Exceptions\CannotDeleteNewsException;
use App\Repositories\Exceptions\InvalidNewsExceception;
use Psr\Log\LoggerInterface;

final class NewsRepository
{
    public function __construct(
        private readonly DatabaseConnectionInterface $databaseConnection,
        private readonly CommentRepository $commentManager,
        private readonly NewsBuilder $newsBuilder,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @return array<News>
     * list all news
     */
    public function listNews(): MultipleNews
    {
        $rows = $this->databaseConnection->select('SELECT * FROM `news`');
        $news = new MultipleNews();
        foreach ($rows as $row) {

            try {
                $news->append($this->newsBuilder
                    ->setBody($row["body"])
                    ->setCreatedAt($row["created_at"])
                    ->setTitle($row["title"])
                    ->setId($row["id"])
                    ->buildExisting());
            }
            catch (InvalidNewsExceception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }

        return $news;
    }

    /**
     * add a record in news table
     */
    public function addNews(string $title, string $body): int|bool
    {
        try {

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
            return (int) $this->databaseConnection->lastInsertId();
        }
        catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return 0;
        }
    }

    /**
     * deletes a news, and also linked comments
     */
    public function deleteNews(int $id): bool
    {
        $this->databaseConnection->startTransaction();
        try {
            $success = $this->commentManager->deleteByNewsId($id);
            if($success) {
                throw new CannotDeleteNewsException();
            }
            $sql = "DELETE FROM `news` WHERE `id`= :id";
            $this->databaseConnection->execute(
                $sql,
                ["id" => $id],
                ["id" => ParameterTypes::TYPE_INT]
            );
            $this->databaseConnection->commit();
            return true;
        }
        catch (\Throwable $throwable) {
            $this->databaseConnection->rollback();
            return false;
        }
    }
}
<?php

namespace App\Repositories;

use App\Database\DatabaseConnectionInterface;
use App\Database\ParameterTypes;
use App\Entity\Comment;
use App\Entity\Comments;
use App\Repositories\Builder\CommentBuilder;
use App\Repositories\Exceptions\InvalidCommentExceception;
use Psr\Log\LoggerInterface;

class CommentRepository
{
    public function __construct(
        private readonly DatabaseConnectionInterface $databaseConnection,
        private readonly CommentBuilder $commentBuilder,
        private readonly LoggerInterface $logger
    ) {
    }

    public function listComments(): Comments
    {
        $rows = $this->databaseConnection->select('SELECT * FROM `comment`');

        $comments = new Comments();
        foreach ($rows as $row) {
            try {
                $comments->append(
                    $this->commentBuilder
                        ->setNewsId((int) $row["news_id"])
                        ->setBody((string) $row["body"])
                        ->setCreatedAt((string) $row["created_at"])
                        ->setId((int) $row["id"])
                        ->buildExisting()
                );
            } catch (InvalidCommentExceception $exceception) {
                $this->logger->warning($exceception->getMessage());
            }
        }

        return $comments;
    }

    public function addCommentForNews(string $body, int $newsId): int
    {

        try {
            $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
            $currentDateTime = new \DateTimeImmutable();
            $this->databaseConnection->execute(
                $sql,
                [
                    "body" => $body,
                    "created_at" => $currentDateTime->format("Y-m-d H:i:s"),
                    "news_id" => $newsId
                ],
                [
                    "body" => ParameterTypes::TYPE_STRING,
                    "created_at" => ParameterTypes::TYPE_STRING,
                    "news_id" => ParameterTypes::TYPE_INT
                ]
            );
            return (int) $this->databaseConnection->lastInsertId();
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return 0;
        }
    }

    public function deleteComment(int $id): bool
    {
        try {
            $sql = "DELETE FROM `comment` WHERE `id`= :id";
            $this->databaseConnection->execute(
                $sql,
                ["id" => $id],
                ["id" => ParameterTypes::TYPE_INT]
            );
            return true;
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return false;
        }
    }

    public function deleteByNewsId(int $newsId): bool
    {
        try {
            $sql = "DELETE FROM `comment` WHERE `news_id`= :news_id";
            $this->databaseConnection->execute(
                $sql,
                ["news_id" => $newsId],
                ["news_id" => ParameterTypes::TYPE_INT]
            );
            return true;
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return false;
        }
    }

    /**
     * @param int $getId
     * @return Comments<Comment>
     */
    public function getCommentsByNewsId(int $getId): Comments
    {
        $rows = $this->databaseConnection->select(
            'SELECT * FROM `comment` where `news_id` = :news_id',
            [":news_id" => $getId],
            [":news_id" => ParameterTypes::TYPE_INT],
        );

        $comments = new Comments();
        foreach ($rows as $row) {
            try {
                $comments->append(
                    $this->commentBuilder
                        ->setNewsId((int) $row["news_id"])
                        ->setBody((string) $row["body"])
                        ->setCreatedAt((string) $row["created_at"])
                        ->setId((int) $row["id"])
                        ->buildExisting()
                );
            } catch (InvalidCommentExceception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        return $comments;
    }
}

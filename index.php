<?php


use App\DatabaseManager\CommentManager;
use App\DatabaseManager\NewsManager;

define('ROOT', __DIR__);

require_once __DIR__ . '/bootstrap.php';

try {
    foreach (NewsManager::getInstance()->listNews() as $news) {
        echo("############ NEWS " . $news->getTitle() . " ############\n");
        echo($news->getBody() . "\n");
        foreach (CommentManager::getInstance()->listComments() as $comment) {
            if ($comment->getNewsId() == $news->getId()) {
                echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
            }
        }
    }
}
catch (\Throwable $throwable) {
    var_dump($throwable->getMessage());
}

$commentManager = CommentManager::getInstance();
$c = $commentManager->listComments();
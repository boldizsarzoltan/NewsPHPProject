<?php


use App\Database\DatabaseConnection;
use App\DatabaseManager\CommentManager;
use App\DatabaseManager\NewsManager;

define('ROOT', __DIR__);

require_once __DIR__ . '/bootstrap.php';

$database = DatabaseConnection::getInstance();
$commentManager = CommentManager::getInstance($database);
$newsManager = NewsManager::getInstance($database, $commentManager);

foreach ($newsManager->listNews() as $news) {
    echo("############ NEWS " . $news->getTitle() . " ############\n");
    echo($news->getBody() . "\n");
    foreach ($commentManager->listComments() as $comment) {
        if ($comment->getNewsId() == $news->getId()) {
            echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
        }
    }
}


$c = $commentManager->listComments();
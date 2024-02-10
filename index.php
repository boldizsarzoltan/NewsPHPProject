<?php


use App\Database\DatabaseConnection;
use App\Repositories\CommentRepository;
use App\Repositories\NewsRepository;

define('ROOT', __DIR__);

require_once __DIR__ . '/bootstrap.php';

$kernel = new \App\Kernel();
$news = $kernel->getNewsRepository()->listNews();
$commentRepository = $kernel->getCommentRepository();
foreach ($kernel->getNewsRepository()->listNews() as $news) {
    echo("############ NEWS " . $news->getTitle() . " ############\n");
    echo($news->getBody() . "\n");
    foreach ($commentRepository->listComments() as $comment) {
        if ($comment->getNewsId() == $news->getId()) {
            echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
        }
    }
}


$c = $commentRepository->listComments();
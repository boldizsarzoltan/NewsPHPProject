<?php

use App\utils\CommentManager;
use App\utils\NewsManager;

define('ROOT', __DIR__ . "/src/");

require_once(ROOT . '/Utils/NewsManager.php');
require_once(ROOT . '/Utils/CommentManager.php');

foreach (NewsManager::getInstance()->listNews() as $news) {
	echo("############ NEWS " . $news->getTitle() . " ############\n");
	echo($news->getBody() . "\n");
	foreach (CommentManager::getInstance()->listComments() as $comment) {
		if ($comment->getNewsId() == $news->getId()) {
			echo("Comment " . $comment->getId() . " : " . $comment->getBody() . "\n");
		}
	}
}

$commentManager = CommentManager::getInstance();
$c = $commentManager->listComments();
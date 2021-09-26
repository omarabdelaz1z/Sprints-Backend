<?php

require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/CommentController.php');

$database = Database::getInstance();
$connection = $database->getConnection();
$commentController = new CommentController($connection);

$comment = $_POST['content'] ?? NULL;
$postID  = $_POST['postID'] ?? NULL;
$userID  = $_POST['userID'] ?? NULL;

if(!$comment || !$postID || !$userID) {
    echo 'error';
    die();
}

$commentController->createComment($comment, $postID, $userID);
require_once('render.php');
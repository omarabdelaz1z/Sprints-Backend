<?php

require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/CommentController.php');

$database = Database::getInstance();
$connection = $database->getConnection();
$commentController = new CommentController($connection);

$commentID = $_POST['commentID'] ?? NULL;
$postID = $_POST['postID'] ?? NULL;

if(!$commentID || !$postID){
    echo 'error';
    die();
}

$commentController->deleteComment($commentID);
require_once('render.php');
<?php

require_once('../path.config.php');
require_once('../utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/PostController.php');

$userID = getUserId();
$postID = intval($_REQUEST['id']);
$type = $_REQUEST['action'];

$database = Database::getInstance();
$connection = $database->getConnection();
$postController = new PostController($connection);


$done = $type === 'like' ? $postController->createLike($postID, $userID) 
       : $postController->deleteLike($postID, $userID);

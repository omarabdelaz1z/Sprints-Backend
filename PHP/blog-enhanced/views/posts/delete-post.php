<?php 

require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH . '/controllers/PostController.php');


if(!isAdmin()){
    header('Location: '.BASE_URL.'/views/posts');
    die();
}

$database = Database::getInstance();
$connection = $database->getConnection();
$postController = new PostController($connection);

$postID = intval($_REQUEST['id']);

$postController->deletePost($postID);
header('Location: '.BASE_URL.'/views/posts');
die();

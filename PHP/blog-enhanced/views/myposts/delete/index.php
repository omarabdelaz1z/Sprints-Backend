<?php
    require_once('../../../path.config.php');
    require_once(BASE_PATH.'/utils/auth/session.php');
    require_once(BASE_PATH.'/utils/database/Database.php');
    require_once(BASE_PATH . '/controllers/PostController.php');

    if(!isset($_REQUEST['id'])){
        header('Location: '.BASE_URL.'/views/myposts');
        die();
    }

    $postID = intval($_REQUEST['id']);

    $database = Database::getInstance();
    $connection = $database->getConnection();
    $postController = new PostController($connection);

    $post = $postController->findPosts(1, 1, ["post_id" => $postID])[0];
    $postUserID = $post->getUserID();

    if(!isUserAuthorized($postUserID)) {
        header('Location: '.BASE_URL);
        die();
    }

    $postController->deletePost($postID);
    header('Location: '.BASE_URL.'/views/myposts');
    die();

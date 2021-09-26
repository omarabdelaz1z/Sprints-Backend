<?php

require_once('../path.config.php');
require_once('../utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/UserController.php');

$userID = getUserId(); // Following ID

$type = $_REQUEST['action'];
$followerID = $_REQUEST['follower_id'];

$database = Database::getInstance();
$connection = $database->getConnection();
$userController = new UserController($connection);

if($type === "follow"){
    $userController->followUser($followerID, $userID);

}elseif ($type === "unfollow"){
    $userController->unfollowUser($followerID, $userID);
}
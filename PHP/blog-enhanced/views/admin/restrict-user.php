<?php
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/UserController.php');

$userID = getUserId();

if(!isUserAuthorized($userID)) {
    header('Location: '.BASE_URL);
    die();
}

$id = intval($_GET['id']) ?? NULL;
$activity = intval($_GET['activity']) ?? NULL;

if(gettype($id) === 'NULL' || gettype($activity) === 'NULL'){
    echo 'error';
    die();
}

$database = Database::getInstance();
$connection = $database->getConnection();
$userController = new UserController($connection);

if($userController->restrictUserActivity($id, $activity)){
    require_once('users.php');
}

else{
    echo "Error";
}

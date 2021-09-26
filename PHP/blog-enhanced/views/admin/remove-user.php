<?php
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/UserController.php');


if(!isAdmin()) {
    header('Location: '.BASE_URL);
    die();
}

$database = Database::getInstance();
$connection = $database->getConnection();
$userController = new UserController($connection);


$userController->deleteUser(intval($_GET['id']));
require_once('users.php');

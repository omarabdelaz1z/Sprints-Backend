<?php
require_once BASE_PATH.'/utils/Database/Database.php';
require_once BASE_PATH.'/controllers/UserController.php';

function signup(array $user){
    $database = Database::getInstance();
    $connection = $database->getConnection();

    $userController = new UserController($connection);
    return $userController->createUser($user);
}

<?php
require_once("../../path.config.php");
require_once BASE_PATH.'/utils/database/Database.php';
require_once BASE_PATH.'/controllers/UserController.php';

function signin($username, $password): ?User {
    $database = Database::getInstance();
    $connection = $database->getConnection();

    $userController = new UserController($connection);
    return $userController->findUser($username, $password);
}
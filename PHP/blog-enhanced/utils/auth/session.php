<?php

require_once(BASE_PATH.'/models/User.php');

function initSession(User $user) {
    if(session_status() != PHP_SESSION_ACTIVE)
        session_start();

    $_SESSION['user'] = $user;

    var_dump($_SESSION['user']);
}

function destroySession() {
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }

    session_destroy();
    header('Location:' . BASE_URL);
    die();
}

function isSessionActive(){
    return $_SESSION['user'];
}

function getUserId() {
    if (session_status() != PHP_SESSION_ACTIVE)
        session_start();

    if (isset($_SESSION['user'])){
        return $_SESSION['user']->getID();
    }

    destroySession();
}

function isUserAuthorized($userID){
    if (session_status() != PHP_SESSION_ACTIVE) session_start();

    if (!isset($_SESSION['user'])) return false;
    
    $user = $_SESSION['user'];
    
    $isAdmin = $user->getRole();
    $ownPost = $user->getID() === $userID;

    return $isAdmin || $ownPost;
}

function isAdmin(){
    if (session_status() != PHP_SESSION_ACTIVE) session_start();

    if (!isset($_SESSION['user'])) return false;

    $user = $_SESSION['user'];

    return $user->getRole();
}


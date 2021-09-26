<?php
require_once('../../path.config.php');

    if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
    }

    session_destroy();
    header('Location:' . BASE_URL . '/views/login');
    die();

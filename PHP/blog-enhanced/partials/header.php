<?php
require_once(BASE_PATH.'/models/User.php');
if (session_status() != PHP_SESSION_ACTIVE)
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="TemplateMo">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

    <title>Stand CSS Blog by TemplateMo</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= BASE_URL.'/vendor/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">  
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="<?= BASE_URL.'/assets/css/fontawesome.css'?>">
    <link rel="stylesheet" href="<?= BASE_URL.'/assets/css/templatemo-stand-blog.css'?>">
    <link rel="stylesheet" href="<?= BASE_URL.'/assets/css/owl.css'?>">

    <script src="<?= BASE_URL.'/vendor/jquery/jquery.min.js'?>"></script>

    <!--

    
TemplateMo 551 Stand Blog

https://templatemo.com/tm-551-stand-blog

-->
</head>

<body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <!-- Header -->
    <header class="">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?=BASE_URL.'/index.php'?>">
                    <h2>Sprints Blog<em>.</em></h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?=BASE_URL.'/index.php'?>">Home
                                <!-- <span class="sr-only">(current)</span> -->
                            </a>
                        </li>
                        <?php if(isset($_SESSION['user'])) {?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL.'/views/posts'?>">Posts</a>
                        </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL.'/views/contact'?>">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=BASE_URL.'/views/about'?>">About Us</a>
                        </li>
                        <?php if (isset($_SESSION['user'])) { ?>
                            <?php if ($_SESSION['user']->getRole()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=BASE_URL.'/views/admin'?>">Admin</a>
                                </li>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL.'/views/myposts'?>">My Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL.'/views/logout'?>">Logout</a>
                            </li>
                        <?php
                        } else
                        {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL.'/views/login/'?>">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?=BASE_URL.'/views/register/'?>">Register</a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <script>
            $(function() {
                let url = window.location.href;
                $('nav ul li a').each(function() {
                if (this.href === url) {
                    $(this).addClass('active');
                }
                });
            });
        </script>
    </header>
<?php
require_once('path.config.php');
require_once('utils/auth/session.php');
require_once('utils/pagination.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/PostController.php');
require_once(BASE_PATH.'/controllers/TagController.php');
require_once(BASE_PATH.'/controllers/CategoryController.php');

$page = intval($_GET['page'] ?? 1);
$limit = intval($_GET['limit'] ?? 3);

$database = Database::getInstance();
$connection = $database->getConnection();

$postController = new PostController($connection);
$categoryController = new CategoryController($connection);
$tagController = new TagController($connection);

$categories = $categoryController->findCategory();
$tags = $tagController->findTag();

$posts = $postController->findPosts($page, $limit, []);

$postsCount = $postController->findPostsCount();
?>
<?php require_once(BASE_PATH.'/partials/header.php'); ?>
    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="main-banner header-text">
        <div class="container-fluid">
            <div class="owl-banner owl-carousel">
                <div class="item">
                    <img src="<?=BASE_URL.'/assets/images/banner-item-01.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Fashion</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Morbi dapibus condimentum</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 12, 2020</a></li>
                                <li><a href="#">12 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?=BASE_URL.'/assets/images/banner-item-02.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Nature</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Donec porttitor augue at velit</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 14, 2020</a></li>
                                <li><a href="#">24 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= BASE_URL.'/assets/images/banner-item-03.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Lifestyle</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Best HTML Templates on TemplateMo</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 16, 2020</a></li>
                                <li><a href="#">36 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= BASE_URL.'/assets/images/banner-item-04.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Fashion</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Responsive and Mobile Ready Layouts</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 18, 2020</a></li>
                                <li><a href="#">48 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?= BASE_URL.'/assets/images/banner-item-05.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Nature</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Cras congue sed augue id ullamcorper</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 24, 2020</a></li>
                                <li><a href="#">64 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <img src="<?=BASE_URL.'/assets/images/banner-item-06.jpg'?>" alt="">
                    <div class="item-content">
                        <div class="main-content">
                            <div class="meta-category">
                                <span>Lifestyle</span>
                            </div>
                            <a href="post-details.html">
                                <h4>Suspendisse nec aliquet ligula</h4>
                            </a>
                            <ul class="post-info">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#">May 26, 2020</a></li>
                                <li><a href="#">72 Comments</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Ends Here -->

    <section class="blog-posts">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="all-blog-posts">
                        <div class="row">
                          
                            <?php
                            foreach ($posts as $post) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="blog-post">
                                        <div class="blog-thumb">
                                            <img src="<?= BASE_URL .'/assets/images/'. $post->getImage() ?>" alt="">
                                        </div>
                                        <div class="down-content">
                                            <span><?= $post->getCategory()->getName() ?></span>
                                            <a href="<?= BASE_URL . '/views/post-details?id=' . $post->getID() ?>">
                                                <h4><?= $post->getTitle() ?></h4>
                                            </a>
                                            <ul class="post-info">
                                                <li><a href="#"><?= $post->getAuthorName() ?? '' ?></a></li>
                                                <li><a href="#"><?= $post->getPublishDate() ?></a></li>
                                                <li><a href="#"><?= count($post->getComments()) ?> Comments</a></li>
                                            </ul>
                                            <p><?= $post->getContent() ?></p>
                                            <?php
                                            if ($post->getTags()) {
                                            ?>
                                                <div class="pos-options">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <ul class="post-tags">
                                                                <li><i class="fa fa-tags"></i></li>
                                                                <?php
                                                                $tags = $post->getTags();
                                                                foreach ($tags as $tag) {
                                                                    ?>
                                                                    <li><a href="<?= BASE_URL . '/posts.php?tag_id=' . $tag->getID() ?>"><?= $tag->getName() ?></a></li>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sidebar-item search">
                                    <form id="search_form" name="gs" method="GET" action="#">
                                        <input type="text" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="sidebar-item categories">
                                    <div class="sidebar-heading">
                                        <h2>Categories</h2>
                                    </div>
                                    <div class="content">
                                        <ul>
                                            <?php
                                            foreach ($categories as $category) {
                                                ?>
                                                <li><a href="<?= BASE_URL . '/posts.php?category_id=' . $category->getID() ?>">- <?= $category->getName() ?></a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="sidebar-item tags">
                                    <div class="sidebar-heading">
                                        <h2>Tag Clouds</h2>
                                    </div>
                                    <div class="content">
                                        <ul>
                                            <?php
                                            foreach ($tags as $tag) {
                                                ?>
                                                <li><a href="<?= BASE_URL . '/posts.php?tag_id=' . $tag->getID() ?>"><?= $tag->getName() ?></a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php require_once(BASE_PATH.'/partials/footer.php'); ?>

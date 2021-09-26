<?php
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/pagination.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/PostController.php');

$page = intval($_GET['page'] ?? 1);
$limit = intval($_GET['limit'] ?? 4);

$database = Database::getInstance();
$connection = $database->getConnection();

$postController = new PostController($connection);
$posts = $postController->findPosts($page, $limit, []);
$postsCount = $postController->findPostsCount();
$pagination = pagination($postsCount, $limit, $page, 4);
?>
<?php require_once(BASE_PATH.'/partials/header.php'); ?>
<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Recent Posts</h4>
                        <h2>Our Recent Blog Entries</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Banner Ends Here -->
<section class="blog-posts">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="all-blog-posts">
                    <div class="row">
                        <?php foreach ($posts as $post) {?>
                            <div class="col-lg-6">
                                <div data-pid="<?= $post->getID() ?>" class="blog-post">

                                    <div class="blog-thumb">
                                        <img src="<?= BASE_URL .'/assets/images/'. $post->getImage() ?>" alt="">
                                    </div>

                                    <div class="down-content">
                                        <span><?= $post->getCategory()->getName() ?? '' ?></span>

                                        <a href="<?=BASE_URL.'/views/post-details?id=' . $post->getID() ?>">
                                            <h4><?= $post->getTitle() ?></h4>
                                        </a>

                                        <ul class="post-info">
                                            <li><a href="#"><?= $post->getAuthorName() ?? '' ?></a></li>
                                            <li><a href="#"><?= $post->getPublishDate() ?? '' ?></a></li>
                                            <li><a href="#"><?= $post->getCommentCount() ?? '' ?> Comments</a></li>
                                            <?php if(isAdmin()) {?>
                                                <a href='<?= BASE_URL.'/views/posts/delete-post.php?id='.$post->getID()?>' onclick="return onclick('are you sure')" class='btn btn-primary'>Delete</a>
                                             <?php } ?>
                                        </ul>
                                        <p><?= $post->getContent() ?? '' ?></p>
                                        <?php
                                        if ($post->getTags() !== null) {
                                        ?>
                                            <div class="post-options">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <ul class="post-tags">
                                                            <li><i class="fa fa-tags"></i></li>
                                                            <?php
                                                            foreach ($post->getTags() as $tag) {
                                                            ?>
                                                                <li><a href="#"><?= $tag->getName() ?? '' ?></a></li>
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
                <?php include(BASE_PATH.'/partials/pagination.php')?>
            </div>
        </div>
    </div>
</section>
<?php require_once(BASE_PATH.'/partials/footer.php'); ?>

<?php

require_once __DIR__.'\..\utils\database.connection.php';
require_once __DIR__.'\..\utils\pagination.php';
require_once __DIR__.'\..\controller\post.controller.php';


try{
    $connection = connect();

    $page =  intval($_GET['page'] ?? 1);
    $limit = intval($_GET['limit'] ?? 1);

    $dataCount = getPostCount($connection);
    $posts = getPosts($connection, $page, $limit);
    $paginationInfo = getPaginationInfo($dataCount, $limit, $page);
    
    mysqli_close($connection);

    // echo json_encode($posts, JSON_PRETTY_PRINT).'<br><br>';
    // echo '{ "Pagination":' . json_encode($paginationInfo, JSON_PRETTY_PRINT).'<br><br>';
    
}catch(mysqli_sql_exception $e){
    echo 'Failure: '.$e->getMessage().'<br><br>';
}

?>


<?php require_once(__DIR__.'\..\layout\header.php'); ?>
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
                <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="GET" action="#">
                        <input type="text" value="<?= $_REQUEST['q'] ?? '' ?>" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="all-blog-posts">
                    <div class="row">
                        <?php
                        foreach ($posts as $post) {
                        ?>
                            <div class="col-lg-6">
                                <div class="blog-post">
                                    <div class="blog-thumb">
                                        <img src="<?= $post['image'] ?>" alt="">
                                    </div>
                                    <div class="down-content">
                                        <span><?= $post['category'] ?></span>
                                        <a href="<?='/post-details.php?id=' . $post['id'] ?>">
                                            <h4><?= $post['title'] ?></h4>
                                        </a>
                                        <ul class="post-info">
                                            <li><a href="#"><?= $post['author'] ?></a></li>
                                            <li><a href="#"><?= $post['publish_date'] ?></a></li>
                                            <li><a href="#"><?= $post['comment_count'] ?> Comments</a></li>
                                        </ul>
                                        <p><?= $post['content'] ?></p>
                                        <?php
                                        if ($post['tags']) {
                                        ?>
                                            <div class="post-options">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <ul class="post-tags">
                                                            <li><i class="fa fa-tags"></i></li>
                                                            <?php
                                                            foreach ($post['tags'] as $tag) {
                                                            ?>
                                                                <li><a href="#"><?= $tag['name'] ?></a></li>
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
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php
                            for($page = $paginationInfo['navigation']['fromPage']; $page <= $paginationInfo['navigation']['untilPage']; $page++) {
                                
                                $disabled = $page === $paginationInfo['currentPage'] ? 'disabled' : '';
                                
                                echo "<li class='page-item'><a class='page-link' href='?page=$page&limit=$limit' $disabled>$page</a></li>"; 
                            }
                        ?>
                    </ul>
            </nav>
            </div>
        </div>
    </div>
</section>

<?php require_once(__DIR__.'\..\layout\footer.php') ?>
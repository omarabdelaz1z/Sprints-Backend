<?php
require_once('../../path.config.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/pagination.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH.'/controllers/PostController.php');

$database = Database::getInstance();
$connection = $database->getConnection();
$postController = new PostController($connection);

$page = intval($_REQUEST['page'] ?? 1);
$limit = intval($_REQUEST['limit'] ?? 3);

$userID = getUserId();
$order_field = $_REQUEST['order_field'] ?? 'publish_date';
$order_direction = $_REQUEST['order_direction'] ?? 'DESC'; 



$filters = [
    'user_id' => $userID,
    'order_direction' => $order_direction,
    'order_field' => $order_field
];

$userPosts = $postController->findPosts($page, $limit, $filters, 'detail');
$userPostsCount = $postController->findPostsCountByUserID($userID);
$pagination = pagination($userPostsCount, $limit, $page, 2);

?>

<?php require_once(BASE_PATH . '/partials/header.php');?>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="heading-page header-text">
        <section class="page-heading">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-content">
                            <h4>My Posts</h4>
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
                            <div class="col-md-2"><a href="add.php" class="btn btn-success">Add Post</a></div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><a href="">Title </a></th>
                                    <th><a href="">Content </a></th>
                                    <th><a class="column_sort" data-column='category_name' data-order href="">Category </a></th>
                                    <th>Tags</th>
                                    <th>Image</th>
                                    <th><a class="column_sort" data-column='publish_date' data-order href=""> Publish Date</a></th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($userPosts as $post) { ?>
                                <tr>
                                    <td><?=$post->getTitle()?></td>
                                    <td><?=$post->getContent()?></td>
                                    <td><?=$post->getCategory()->getName()?></td>

                                    <td>
                                    <?php 
                                        $tags = $post->getTags();
                                        foreach($tags as $tag) { 
                                    ?>
                                        <span class='tag'><?=$tag->getName()?></span>
                                    <?php } ?>
                                    </td>

                                    <td><img src='<?= BASE_URL.'/assets/post-images/'.$post->getImage() ?>' width='200' height='200'/></td>
                                    <td><?=$post->getPublishDate()?></td>
                                    <td>
                                        <a href='<?= BASE_URL.'/views/myposts/update?id='.$post->getID()?>' class='btn btn-primary'>Edit</a>
                                        <a onclick='return confirm("Are you sure ?")' href='<?= BASE_URL.'/views/myposts/delete?id='.$post->getID()?>' class='btn btn-danger'>Delete</a>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-12">
                            <?php include(BASE_PATH.'/partials/pagination.php')?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    $(document).ready(function() {
        $(document).on('click', '.column_sort', function(){
            const order_field = $(this).data("column");
            const order_direction = $(this).data("order");

            const icon = order === 'asc' 
                                ? 'glyphicon glyphicon-arrow-up' 
                                : 'glyphicon glyphicon-arrow-down';

            $.ajax({
                url: '',
                method: "POST",
                data: {order_field, order_direction},
                success: function(posts){

                }
            })
        });
    });
</script>

<?php require_once(BASE_PATH . '/partials/footer.php') ?>
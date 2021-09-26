<?php
require_once('../../../path.config.php');
require_once(BASE_PATH.'/utils/general.php');
require_once(BASE_PATH.'/utils/auth/session.php');
require_once(BASE_PATH.'/utils/database/Database.php');
require_once(BASE_PATH . '/controllers/PostController.php');
require_once(BASE_PATH . '/controllers/TagController.php');
require_once(BASE_PATH . '/controllers/CategoryController.php');

if(!isset($_REQUEST['id'])){
    header('Location: '.BASE_URL.'/views/myposts');
    die();
}

$postID = intval($_REQUEST['id']);

$database = Database::getInstance();
$connection = $database->getConnection();
$postController = new PostController($connection);

$post = $postController->findPosts(1, 1, ['post_id' => $postID])[0];
$postUserID = $post->getUserID();

if(!isUserAuthorized($postUserID)) {
    header('Location: '.BASE_URL);
    die();
}

$tagIDs = array_map(function($tag){
    return $tag->getID();
}, $post->getTags());

$categoryController = new CategoryController($connection);
$categories = $categoryController->findCategory();

$tagController = new TagController($connection);
$tags = $tagController->findTag();

if ($_POST) {
    $uplodadedImage = getUploadedImage($_FILES);

    $sentData = [
        "title" => $_POST['title'],
        "content" => $_POST['content'],
        "category_id" => intval($_POST['category']),
        "image" => $uplodadedImage ?? "",
        "id" => $postID,
    ];
    
    $isUpdated = $postController->updatePost($sentData);
    $isDeleted = $tagController->deletePostTags($postID);
    $isInserted = $tagController->createPostTags($postID, $_POST['tags']);

    if ($isUpdated && $isDeleted && $isInserted) {
        header('Location: '.BASE_URL.'/views/myposts');
        die();
    }
}

require_once(BASE_PATH . '/partials/header.php');
?>
<!-- Page Content -->
<!-- Banner Starts Here -->
<div class="heading-page header-text">
    <section class="page-heading">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-content">
                        <h4>Edit Post</h4>
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
                        <div class="col-sm-12">
                            <form method="POST" enctype="multipart/form-data" accept="<?=$_SERVER['PHP_SELF']?>">
                                <input name="title" placeholder="title" class="form-control" value="<?= $post->getTitle() ?? "" ?>" />
                                <textarea name="content" placeholder="type description" class="form-control"><?= $post->getContent() ?? "" ?></textarea>
                                <?php if ($post->getImage()) { ?>
                                    <img src="<?= $post->getImage() ? BASE_URL . '/assets/post-images/' . $post->getImage() : "" ?>" alt="missing-thumbnail" width="200" height="200">
                                <?php } ?>
                                <label>Upload Image<input type="file" name="image" /></label><br />

                                <select name="category" class="form-control">
                                    <option value="">Select category</option>
                                    <?php
                                    foreach ($categories as $category) {
                                        if ($post->getCategory()->getID() === intval($category->getID())) {
                                            echo "<option value='{$category->getID()}' selected>{$category->getName()}</option>";
                                        } else {
                                            echo "<option value='{$category->getID()}'>{$category->getName()}</option>";
                                        }
                                    }
                                    ?>
                                </select>

                                <select name="tags[]" multiple class="form-control">

                                    <?php foreach ($tags as $tag) {
                                        if (in_array($tag->getID(), $tagIDs)) {
                                            echo "<option value='{$tag->getID()}' selected>{$tag->getName()}</option>";
                                        } else {
                                            echo "<option value='{$tag->getID()}' >{$tag->getName()}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-success">Edit Post</button>
                                <a href="<?php BASE_URL.'/views/myposts/' ?>" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once(BASE_PATH . '/partials/footer.php') ?>
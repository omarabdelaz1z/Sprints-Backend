<?php
require_once('../config.php');
require_once(BASE_PATH . '/logic/posts.php');
require_once(BASE_PATH . '/logic/tags.php');
require_once(BASE_PATH . '/logic/categories.php');

function getUserId()
{
    if (session_status() != PHP_SESSION_ACTIVE) session_start();
    if (isset($_SESSION['user'])) return $_SESSION['user']['id'];
    return 0;
}

function tagToIds($tag)
{
    return intval($tag['id']);
}

$postID = intval($_GET['id']);
$post = getPostByID($postID);
$postTags = getPostTags($postID);
$tagIds = array_map('tagToIds', $postTags);

$categories = getCategories();
$tags = getTags();


if ($_POST) {
    $image = getUploadedImage($_FILES);

    $submittedData = [
        "id" => $postID,
        "title" => $_POST['title'],
        "content" => $_POST['content'],
        "category_id" => intval($_POST['category']),
        "image" => $image ?? "",
        "tags" => $_POST['tags'] ?? [],
    ];

    $isUpdated = editPost($submittedData);
    $isDeleted = deleteTagsByPostID($postID);

    $isInserted = insertNewTagsToPost($postID, $_POST['tags']);

    if ($isUpdated && $isDeleted && $isInserted) {
        header('Location: index.php');
        die();
    }
}

require_once(BASE_PATH . '/layout/header.php');
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
                            <form method="POST" enctype="multipart/form-data">
                                <input name="title" placeholder="title" class="form-control" value="<?= $post['title'] ?? "" ?>" />
                                <textarea name="content" placeholder="type description" class="form-control"><?= $post['content'] ?? "" ?></textarea>
                                <?php if ($post['image']) { ?>
                                    <img src="<?= $post['image'] ? BASE_URL . 'post_images/' . $post['image'] : "" ?>" alt="missing-thumbnail" width="200" height="200">
                                <?php } ?>
                                <label>Upload Image<input type="file" name="image" /></label><br />

                                <select name="category" class="form-control">
                                    <option value="">Select category</option>
                                    <?php
                                    foreach ($categories as $category) {
                                        if ($post['category_id'] === intval($category['id'])) {
                                            echo "<option value='{$category['id']}' selected>{$category['name']}</option>";
                                        } else {
                                            echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>

                                <select name="tags[]" multiple class="form-control">

                                    <?php foreach ($tags as $tag) {
                                        if (in_array($tag['id'], $tagIds)) {
                                            echo "<option value='{$tag['id']}' selected>{$tag['name']}</option>";
                                        } else {
                                            echo "<option value='{$tag['id']}' >{$tag['name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-success">Edit Post</button>
                                <a href="index.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once(BASE_PATH . '/layout/footer.php') ?>
<?php
require_once BASE_PATH.'/models/Post.php';
require_once BASE_PATH.'/models/Category.php';
require_once BASE_PATH.'/models/Comment.php';
require_once BASE_PATH.'/models/Tag.php';

function toPost($post) {
    [
        "id" => $id,
        "user_id" => $userID,
        "author_name" => $authorName,
        "likeCount" => $likesCount,
        "content" => $content,
        "category_id" => $categoryID,
        "category_name" => $categoryName,
        "title" => $title,
        "publish_date" => $publishDate,
        "image" => $image,
        "tags" => $tags
    ] = $post;

    $comments = $post['comments'] ?? null;
    $commentCount = $post["commentCount"] ?? null;
    $createdPost = new Post($id, $userID, $authorName,  $title, $content, $image, $publishDate);
    
    $createdPost->setLikesCount($likesCount);
    $createdPost->setTags($tags);

    $category = new Category($categoryID, $categoryName);
    $createdPost->setCategory($category);


    if($comments !== null) $createdPost->setComments($comments);

    if($commentCount !== null) $createdPost->setCommentCount($commentCount);

    return $createdPost;
}

function toCategory($category) {
    return new Category($category['id'], $category['name']);
}

function toComment($comment) {
    [
        "id" => $id,
        "comment" => $text,
        "author_name" => $authorName,
        "comment_date" => $commentDate,
        "post_id" => $postID,
        "user_id" => $userID,
        "likes_count" => $likesCount 
    ] = $comment;
    
    $commentObject = new Comment($id, $userID, $postID, $text, $commentDate);
    $commentObject->setLikesCount($likesCount);
    $commentObject->setAuthorName($authorName);
    return $commentObject;
}

function toTag($tag) {
    return new Tag($tag['id'], $tag['name']);
}
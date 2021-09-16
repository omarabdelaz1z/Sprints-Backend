<?php

function getPostCount($connection){
    $COUNT_QUERY = "SELECT COUNT(1) AS count FROM post";  
    
    $resultSet = mysqli_query($connection, $COUNT_QUERY); 
    if(!$resultSet) return 0;
    
    $count = mysqli_fetch_array($resultSet, MYSQLI_ASSOC)['count'];
    mysqli_free_result($resultSet);
    
    return $count;
}

function getPostTags($postID, $connection){
    $TAGS_QUERY = "SELECT t.name FROM post_tag pt INNER JOIN tag t ON t.id=pt.tag_id WHERE post_id=?";
    $statement = mysqli_prepare($connection, $TAGS_QUERY);

    mysqli_stmt_bind_param($statement, "i", $postID);
    mysqli_stmt_execute($statement);

    $resultSet = mysqli_stmt_get_result($statement);
    if(!$resultSet) return [];

    $tags = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
    mysqli_free_result($resultSet);

    return $tags;
}

function assignTagsToPost($post, $connection){
    $postID = $post['id'];

    $tags = getPostTags($postID, $connection);
    $post['tags'] = $tags;

    return $post;
}

function getCommentCountPerPost($postID, $connection){
    $COMMENT_QUERY = "SELECT count(1) AS comment_count FROM comment WHERE post_id=?";
    $statement = mysqli_prepare($connection, $COMMENT_QUERY);

    mysqli_stmt_bind_param($statement, "i", $postID);
    mysqli_stmt_execute($statement);

    $resultSet = mysqli_stmt_get_result($statement);
    if(!$resultSet) return 0;

    $commentCount = mysqli_fetch_assoc($resultSet)['comment_count'];
    mysqli_free_result($resultSet);

    return $commentCount;
}

function assignCommentsToPost($post, $connection){
    $postID = $post['id'];

    $commentCount = getCommentCountPerPost($postID, $connection);
    $post['comment_count'] = $commentCount;

    return $post;
}

function getPosts($connection, $page, $limit){
    $PAGE_QUERY = "SELECT
        p.id as id, title, content, c.name category, image, publish_date, u.name author, CASE u.type WHEN 0 THEN 'Admin' WHEN 1 THEN 'Author' END AS role 
    FROM post p INNER JOIN user u ON p.user_id = u.id INNER JOIN category c ON c.id = p.category_id ORDER BY publish_date DESC LIMIT ?, ?";
    
    $statement = mysqli_prepare($connection, $PAGE_QUERY); 

    mysqli_stmt_bind_param($statement, "ii", $offset, $limit);
    $offset = $limit * ($page - 1);

    mysqli_stmt_execute($statement);
    
    $resultSet = mysqli_stmt_get_result($statement);
    if(!$resultSet) return [];

    $posts = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);

    $postsWithAttachedTags = array_map(function($post) use ($connection) {
        return assignTagsToPost($post, $connection);
    }, $posts);


    $postsWithAttachedTagsAndComments = array_map(function($post) use ($connection){
        return assignCommentsToPost($post, $connection);
    }, $postsWithAttachedTags);
    

    mysqli_free_result($resultSet);

    return $postsWithAttachedTagsAndComments;
}
<?php

function getPostsCount($connection){
    $COUNT_QUERY = "SELECT COUNT(1) AS count FROM post";  
    
    $resultSet = mysqli_query($connection, $COUNT_QUERY); 
    if(!$resultSet) return 0;
    
    $count = mysqli_fetch_array($resultSet, MYSQLI_ASSOC)['count'];
    mysqli_free_result($resultSet);
    
    return $count;
}


function getPosts($connection, $page, $limit){
    $PAGE_QUERY = "SELECT * FROM post ORDER BY id LIMIT ?, ?"; 
    $statement = mysqli_prepare($connection, $PAGE_QUERY); 
    
    mysqli_stmt_bind_param($statement, "ii", $offset, $limit);
    $offset = $limit * ($page - 1);

    mysqli_stmt_execute($statement);
    
    $resultSet = mysqli_stmt_get_result($statement);
    if(!$resultSet) return [];

    $posts = mysqli_fetch_all($resultSet, MYSQLI_ASSOC);
    mysqli_free_result($resultSet);

    return ["data" => $posts];
}
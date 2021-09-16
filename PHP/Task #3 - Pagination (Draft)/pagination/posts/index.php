<?php

require_once __DIR__.'\..\utils\database.connection.php';
require_once __DIR__.'\..\utils\pagination.php';
require_once __DIR__.'\..\controller\post_controller.php';


try{
    $connection = connect();

    $page = intval($_GET['page']) ?? 1;
    $limit = intval($_GET['limit']) ?? 6;

    $dataCount = getPostsCount($connection);
    $data = getPosts($connection, $page, $limit);
    $paginationInfo = getPaginationInfo($dataCount, $limit, $page);

    mysqli_close($connection);

    echo json_encode($data, JSON_PRETTY_PRINT).'<br><br>';
    echo '{ "Pagination":' . json_encode($paginationInfo, JSON_PRETTY_PRINT).'<br><br>';
    
}catch(mysqli_sql_exception $e){
    echo 'Failure: '.$e->getMessage().'<br>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                    for($page = $paginationInfo['navigation']['fromPage']; $page <= $paginationInfo['navigation']['untilPage']; $page++) {
                        if($page === $paginationInfo['currentPage']){
                            echo "<li class='page-item'><a class='page-link' href='#'>$page</a></li>";
                        }
                        else{
                            echo "<li class='page-item'><a class='page-link' href='?page=$page&limit=$limit'>$page</a></li>";
                        }
                    }
                ?>
            </ul>
        </nav>
</body>
</html>


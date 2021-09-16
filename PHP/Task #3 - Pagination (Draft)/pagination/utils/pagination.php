<?php

define('PAGE_THRESHOLD', 5);

// From Page .... UntilPage: To be displayed on page
function getNavigationBoundaries($page, $pageCount){
    $fromPage = $page === 1 ? 1 : $page - 1;
    $untilPage = $fromPage + PAGE_THRESHOLD;

    if($untilPage > $pageCount) {
        $untilPage = $pageCount;
        $fromPage = $untilPage - PAGE_THRESHOLD;
    }

    return ["fromPage" => $fromPage, "untilPage" => $untilPage];
}

function getPageCount($dataCount, $dataPerPage){
    return CEIl($dataCount / $dataPerPage);
}

function getPaginationInfo($dataCount, $dataPerPage, $currentPage) {
    $pageCount = getPageCount($dataCount, $dataPerPage);
    $navigation = getNavigationBoundaries($currentPage, $pageCount);

    return [
            "totalPages" => $pageCount,
            "navigation" => $navigation,
            "currentPage" => $currentPage,
            "dataCount" => $dataCount
    ];
}

// SELECT COUNT(1) AS COUNT FROM posts;
// SELECT * FROM posts ORDER BY publish_date DESC LIMIT $1, $2 

/**
 * Response = {
 *  data => posts[],
 *  pagination => 
 *          [
 *               dataCount,
 *               totalPages,
 *               fromPage, 
 *               untilPage, 
 *               currentPage
 *          ] 
 * } 
 */
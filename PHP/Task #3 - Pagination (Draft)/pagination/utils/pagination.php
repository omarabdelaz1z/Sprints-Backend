<?php

define('PAGE_THRESHOLD', 2); // Number of Pages to display

// From Page .... UntilPage: To be displayed on page
function getNavigationBoundaries($page, $pageCount){
    $fromPage = $page === 1 ? 1 : $page - 1;
    $untilPage = $fromPage + PAGE_THRESHOLD;

    if($untilPage > $pageCount) {
        $untilPage = $pageCount;
        $adjust = $untilPage - PAGE_THRESHOLD;
        $fromPage =  $adjust <= 0 ? 1 : $adjust;
    }

    return ["fromPage" => $fromPage, "untilPage" => $untilPage];
}

function getPageCount($dataCount, $dataPerPage){
    return intval(CEIl($dataCount / $dataPerPage));
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
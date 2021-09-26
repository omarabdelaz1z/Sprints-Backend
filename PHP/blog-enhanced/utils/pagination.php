<?php

function pageCount ($dataCount, $dataPerPage) {
    return intval(ceil($dataCount / $dataPerPage));
};

function navigation($page, $pageCount, $threshold)  {
    $fromPage = $page === 1 ? 1 : $page - 1;
    $untilPage = $fromPage + $threshold;

    if ($untilPage > $pageCount) {
        $untilPage = $pageCount;
        $adjust = $untilPage - $threshold;
        $fromPage = $adjust <= 0 ? 1 : $adjust;
    }

    return ["fromPage" => $fromPage, "untilPage" => $untilPage ];
};

function showingFrom ($dataPerPage, $currentPage) {
    return $dataPerPage * ($currentPage - 1) + 1;
};

function showingUntil($dataPerPage, $currentPage, $dataCount) {
    return $dataPerPage * $currentPage > $dataCount
        ? $dataCount
        : $dataPerPage * $currentPage;
};

function pagination($dataCount, $dataPerPage, $currentPage, $threshold) {
    $numberOfPages = pageCount($dataCount, $dataPerPage);
    $boundaries = navigation($currentPage, $numberOfPages, $threshold);

    $from = showingFrom($dataPerPage, $currentPage);
    $until = showingUntil($dataPerPage, $currentPage, $dataCount);

    $page = ["previous" => $currentPage - 1 === 0 ? -1 : $currentPage - 1,
             "current" => $currentPage,
             "next" => $currentPage + 1 > $numberOfPages ? -1 : $currentPage + 1
    ];

    return ["numberOfPages" => $numberOfPages,
        "navigation" => $boundaries,
        "dataCount" => $dataCount,
        "page" => $page,
        "showingFrom"  => $from,
        "showingUntil" => $until];
}

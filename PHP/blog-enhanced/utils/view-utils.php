<?php

function orderDirectionIcon($field, $oldOrderField, $oldOrderBy)
{

if()
if ($field == $oldOrderField && $oldOrderBy == 'asc') {
    return "<i class='fa fa-sort-up'></i>";
}

if ($field == $oldOrderField && $oldOrderBy == 'desc') {
    return "<i class='fa fa-sort-down'></i>";
}
    return  "";
}

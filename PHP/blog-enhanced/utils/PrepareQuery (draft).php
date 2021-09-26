<?php

const SELECT = " SELECT ";
const FROM = " FROM ";
const INNER_JOIN = " INNER JOIN ";
const WHERE = " WHERE ";
const ORDER_BY = " ORDER BY ";
const LIMIT = " LIMIT ";

function white_list(&$value, $allowed, $message) {
    if ($value === null) return $allowed[0];

    $key = array_search($value, $allowed, true);

    if ($key === false) throw new InvalidArgumentException($message);

    else return $value;
}

class PrepareQuery
{
    public static function prepareSelect(array $parameters): ?string {
        [
            "COLUMNS" => $COLUMNS,
            "FROM" => $TABLE_NAME,
            "INNER_JOIN" => $TABLES,
            "WHERE" => $CONDITIONS,
            "ORDER_BY" => $ORDER,
            "LIMIT" => $LIMIT
        ]= $parameters;

        $QUERY = SELECT.$COLUMNS.FROM.$TABLE_NAME;

        if(isset($INNER_JOIN)) $QUERY.=INNER_JOIN.$TABLES;

        if(isset($WHERE)) $QUERY.=WHERE.PrepareQuery::whereClause($CONDITIONS);

        if(isset($ORDER)){
            $ORDER_FIELD = white_list($COLUMN, $ORDER['ALLOWED'], "Invalid Field Name");
            $ORDER_DIRECTION = white_list($COLUMN, ['ASC', 'DSC'], "Invalid Order By Direction");
            $QUERY.=ORDER_BY.$ORDER_FIELD.$ORDER_DIRECTION;
        }

        if($LIMIT) $QUERY.=LIMIT."?, ? ";

        return $QUERY;
    }

    private static function whereClause(array $conditions, string $type=" AND "): string{
        return implode($type, $conditions);
    }
}


<?php
require_once 'database.config.php';


function connect(){
    try{
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        return $connection;
    }
    catch(mysqli_sql_exception $e){
        throw $e;
    }
}
<?php

require_once BASE_PATH.'/utils/database/config.php';
mysqli_report(MYSQLI_REPORT_ERROR| MYSQLI_REPORT_STRICT);

class Database
{
    private static ?Database $instance = null;
    private ?mysqli $connection = null;

    private function __construct(){
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        $this->connection->debug(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_ERROR | MYSQLI_REPORT_ALL);

        if($this->connection->error){
            exit($this->connection->error);
        }
    }
    private function __clone(){}

    public static function getInstance(): Database {
        if(!self::$instance){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->connection;
    }
}

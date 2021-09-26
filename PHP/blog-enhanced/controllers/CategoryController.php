<?php
require_once BASE_PATH.'/utils/ToEntity.php';

class CategoryController
{
    private mysqli $connection;

    /**
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection){
        $this->connection = $connection;
    }

    public function findCategory(): array{
        $QUERY = "SELECT * FROM categories";
        $resultSet = $this->connection->query($QUERY);

        if(!$resultSet) return [];

        $categories = $resultSet->fetch_all(MYSQLI_ASSOC);

        $categoryObjects = array_map('toCategory', $categories);
        return $categoryObjects;
    }
}
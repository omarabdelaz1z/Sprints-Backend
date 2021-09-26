<?php
require_once BASE_PATH.'/utils/ToEntity.php';

class TagController
{
    private mysqli $connection;

    /**
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection){
        $this->connection = $connection;
    }

    public function findTagsByPostID(int $postID): array {
        $QUERY = "SELECT t.id id, t.name name 
                  FROM post_tags pt INNER JOIN tags t ON t.id = pt.tag_id
                  WHERE pt.post_id=?";

        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $postID);
        $preparedStatement->execute();

        $resultSet = $preparedStatement->get_result();

        if(!$resultSet) return [];

        $tags = $resultSet->fetch_all(MYSQLI_ASSOC);
        return array_map('toTag', $tags);
    }

    public function findTag(){
        $QUERY = "SELECT * FROM tags";
        $resultSet = $this->connection->query($QUERY);

        if(!$resultSet) return [];

        $tags = $resultSet->fetch_all(MYSQLI_ASSOC);
        return array_map('toTag', $tags);
    }

    
    public function deletePostTags(int $postID) {
        $QUERY = "DELETE FROM post_tags WHERE post_id=?";

        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param('i', $postID);
        $preparedStatement->execute();

        if($preparedStatement->error){
            var_export($preparedStatement);
            exit;
            return false;
        }

        return true;
    }

    public function createPostTags($postID, $tagIDs) {
        $QUERY = "INSERT INTO post_tags(post_id, tag_id) VALUES (?, ?)";
        $preparedStatement = $this->connection->prepare($QUERY);

        foreach ($tagIDs as $tagID) {
            $id = intval($tagID);
            $preparedStatement->bind_param('ii', $postID, $id);
            $preparedStatement->execute();

            if($preparedStatement->error){
                var_export($preparedStatement);
                exit;
                return false;
            }
        }

        if($preparedStatement->error){
            var_export($preparedStatement);
            exit;
            return false;
        }

        return true;
    }
}
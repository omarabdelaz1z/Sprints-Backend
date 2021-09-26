<?php
require_once(BASE_PATH.'/utils/ToEntity.php');
require_once(BASE_PATH.'/models/Comment.php');

class CommentController
{
    private mysqli $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    public function findCommentsByPostId(int $postID): array{
        $QUERY = "SELECT u.name as author_name, c.id id, comment, comment_date, c.post_id post_id, c.user_id user_id
                  FROM comments c INNER JOIN users u ON c.user_id = u.id INNER JOIN posts p ON p.id = c.post_id
                  WHERE c.post_id=? ORDER BY comment_date DESC";

        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $postID);

        $preparedStatement->execute();
        $resultSet = $preparedStatement->get_result();

        if(!$resultSet) return [];

        $comments = $resultSet->fetch_all(MYSQLI_ASSOC);

        for($i = 0; $i < count($comments); $i++){
            $comments[$i]['likes_count'] = $this->getCommentLikes(intval($comments[$i]['id']));
        }

        return array_map('toComment', $comments);
    }

    public function getCommentsCount(int $postId) {
        $QUERY = "SELECT COUNT(0) as count FROM comments WHERE post_id=?";
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $postId);
        
        $preparedStatement->execute();
        $resultSet = $preparedStatement->get_result();

        return intval($resultSet->fetch_array(MYSQLI_ASSOC)['count']);
    }

    public function createComment($comment, $postID, $userID) { 
        $QUERY = "INSERT INTO comments (comment, post_id, user_id) VALUES (?, ?, ?)";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("sii", $comment, $postID, $userID);
        
        if($preparedStatement->execute()){
            $id = $preparedStatement->insert_id;
            return new Comment($id, $userID, $postID, $comment, date('Y-m-d H:i:s'));
        }
    }

    public function deleteComment($commentID): bool{
        $QUERY = "DELETE FROM comments WHERE id=?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $commentID);

        return $preparedStatement->execute();
    }

    public function likeComment(int $commentID, int $userID): void{
        try{
            $QUERY = "INSERT INTO comment_likes (comment_id, user_id) VALUES (?,?)";
            $preparedStatement = $this->connection->prepare($QUERY);

            $preparedStatement->bind_param("ii", $commentID, $userID);
            $preparedStatement->execute();
        }
        catch (mysqli_sql_exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function dislikeComment(int $commentID, int $userID): void{
        try{
            $QUERY = "DELETE FROM comment_likes WHERE comment_id=? AND user_id=?";
            $preparedStatement = $this->connection->prepare($QUERY);

            $preparedStatement->bind_param("ii", $commentID, $userID);
            $preparedStatement->execute();
        }
        catch (mysqli_sql_exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function getCommentLikes($commentID){
        $QUERY = "SELECT COUNT(0) as count FROM comment_likes WHERE comment_id=?";
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $commentID);
        
        $preparedStatement->execute();
        $resultSet = $preparedStatement->get_result();

        return intval($resultSet->fetch_array(MYSQLI_ASSOC)['count']);
    }
}
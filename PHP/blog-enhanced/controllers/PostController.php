<?php
require_once('CommentController.php');
require_once(BASE_PATH.'/models/Post.php');
require_once(BASE_PATH.'/models/Category.php');
require_once(BASE_PATH.'/models/Tag.php');
require_once(BASE_PATH.'/utils/ToEntity.php');
require_once(BASE_PATH.'/controllers/TagController.php');
require_once(BASE_PATH.'/controllers/CategoryController.php');


class PostController
{
    private mysqli $connection;

    /**
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection){
        $this->connection = $connection;
    }

    public function updatePost(array $sentData): bool {
        $QUERY = "UPDATE posts SET title=? , content=? , category_id=? , image=? WHERE id=?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("ssisi", $sentData['title'], $sentData['content'], $sentData['category_id'], $sentData['image'], $sentData['id']);

        $preparedStatement->execute();

        if ($preparedStatement->error) {
            var_export($preparedStatement->error);
            return false;
        }

        return true;
    }

    public function deletePost(int $postID): bool {
        $QUERY = "DELETE FROM posts WHERE id=?";
        $preparedStatement = $this->connection->prepare($QUERY);

        $preparedStatement->bind_param("i", $postID);
        $preparedStatement->execute();


        if ($preparedStatement->error) {
            var_export($preparedStatement->error);
            return false;
        }

        return true;
    }

    public function findPostsCount() :int{
        $QUERY = "SELECT COUNT(1) as count FROM posts p";
        $resultSet = $this->connection->query($QUERY);

        if(!$resultSet) return 0;

        return intval($resultSet->fetch_array(MYSQLI_ASSOC)['count']);
    }

    public function findPosts(int $page, int $limit, $filters, $view='overview'): array {        
        $orderField = $filters['order_field'] ?? "publish_date";
        $orderDirection = $filters['order_by'] ?? "DESC";
        $userID = $filters['user_id'] ?? NULL;
        $postID = $filters['post_id'] ?? NULL;
        $categoryID = $filters['category_id'] ?? NULL;
        
        $QUERY = "SELECT p.id as id, user_id, title, content, c.id category_id, c.name category_name, image, publish_date, u.name as author_name

        FROM posts p 
            INNER JOIN users u ON p.user_id = u.id 
            INNER JOIN categories c ON c.id = p.category_id

        WHERE 
            c.id = IFNULL(?, c.id) 
            AND
            p.id = IFNULL(?, p.id)
            AND
            u.id = IFNULL(?, u.id)  ORDER BY ".$orderField." ".$orderDirection." LIMIT ?, ?";

    
        $statement = $this->connection->prepare($QUERY);
        $offset = $limit * ($page - 1);
        
        $statement->bind_param("iiiii", $categoryID, $postID, $userID, $offset, $limit);
        $statement->execute();
        $resultSet = $statement->get_result();

        if(!$resultSet) exit($this->connection->error);

        $POSTS = $resultSet->fetch_all(MYSQLI_ASSOC);

        $tagController = new TagController($this->connection);
        $commentController = new CommentController($this->connection);

        for($i = 0; $i < count($POSTS); $i++) {
            $postID = $POSTS[$i]['id'];

            $POSTS[$i]['tags'] = $tagController->findTagsByPostID($postID);
            $POSTS[$i]['likeCount'] = $this->getLikesCount($postID);
            
            if($view === 'overview')
                $POSTS[$i]['commentCount'] = $commentController->getCommentsCount($postID);
            
            else
                $POSTS[$i]['comments'] = $commentController->findCommentsByPostId($postID);
        }   
        
        return array_map('toPost', $POSTS);
    }

    public function findPostsCountByUserID(int $userID): int{
        $QUERY = "SELECT COUNT(1) as count FROM posts p INNER JOIN users u ON p.user_id = u.id WHERE p.user_id=?";

        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $userID);
        $preparedStatement->execute();
        $resultSet = $preparedStatement->get_result();

        if(!$resultSet) return 0;

        return intval($resultSet->fetch_array(MYSQLI_ASSOC)['count']);
    }

    public function getLikesCount(int $postID): int{
        $QUERY = "SELECT COUNT(1) as count FROM likes WHERE post_id=?";

        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $postID);
        $preparedStatement->execute();

        $resultSet = $preparedStatement->get_result();

        if(!$resultSet) return 0;

        return intval($resultSet->fetch_array(MYSQLI_ASSOC)['count']);
    }

    public function createLike(int $postID, int $userID): void{
        try{
            $QUERY = "INSERT INTO likes (post_id, user_id) VALUES (?,?)";
            $preparedStatement = $this->connection->prepare($QUERY);

            $preparedStatement->bind_param("ii", $postID, $userID);
            $preparedStatement->execute();
        }
        catch (mysqli_sql_exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function deleteLike(int $postID, int $userID): void{
        try{
            $QUERY = "DELETE FROM likes WHERE post_id=? AND user_id=?";
            $preparedStatement = $this->connection->prepare($QUERY);

            $preparedStatement->bind_param("ii", $postID, $userID);
            $preparedStatement->execute();
        }
        catch (mysqli_sql_exception $e){
            echo $e->getMessage();
            exit;
        }
    }


    public function isLikedBy($userID, $postID){
        $QUERY = "SELECT COUNT(1) as count FROM likes WHERE user_id = ? AND post_id = ? LIMIT 1";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("ii", $userID, $postID);
        $preparedStatement->execute();

        $resultSet = $preparedStatement->get_result();
        
        return $resultSet->fetch_array(MYSQLI_ASSOC)['count'] === 1;
    }
}
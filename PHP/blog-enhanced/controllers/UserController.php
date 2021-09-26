<?php

require_once BASE_PATH.'/models/User.php';

class UserController{
    private mysqli $connection;

    public function __construct(mysqli $connection){
        $this->connection = $connection;
    }

    public function findUser(string $username, string $password): ?User {
        $QUERY = "SELECT id, name, username, email, type, active FROM users WHERE username=? AND password= md5(?)";
        $preparedStatement = $this->connection->prepare($QUERY);

        $preparedStatement->bind_param("ss", $username, $password);
        $preparedStatement->execute();

        $resultSet = $preparedStatement->get_result();

        if($resultSet->num_rows === 0){
            return null;
        }

        $user = $resultSet->fetch_array(MYSQLI_ASSOC);
        $userObject = new User($user['id'], $user['name'], $user['username'], $user['email'], null);
        $userObject->setRole($user['type']);
        $userObject->setActive($user['active']);
        
        return $userObject;
    }

    public function createUser(array $user) {
        try{
            $QUERY = "INSERT INTO users(name, username, email, password) VALUES (?, ?, ?, md5(?))";
            $preparedStatement = $this->connection->prepare($QUERY);

            $preparedStatement->bind_param("ssss", $name, $username, $email, $password);
            ["username" => $username, "name"=> $name, "email"=>$email, "password"=> $password] = $user;

           $preparedStatement->execute();
           $id = $this->connection->insert_id;
           return new User($id, $name, $username, $email, null);
        }
        catch (mysqli_sql_exception $e){
            return $user;
        }
    }

    public function findAllUsers(): array{
        $QUERY = "SELECT id, name, email, type, active FROM users";
        $resultSet = $this->connection->query($QUERY);
        if(!$resultSet) return [];

        $users = $resultSet->fetch_all(MYSQLI_ASSOC);
    
        return $users;
    }

    /** restrict user activity: 
     * 1: unblocked 
     * 0: blocked
     */
    public function restrictUserActivity(int $userID, int $restriction=0): bool {
        $QUERY = "UPDATE users SET active = ? WHERE id= ?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("ii", $restriction, $userID);
        $preparedStatement->execute();

        if ($preparedStatement->error) {
            var_export($preparedStatement->error);
            return false;
        }

        return true;
    }

    

    public function deleteUser(int $userID): bool {
        $QUERY = "DELETE FROM users WHERE id=?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $userID);

        $preparedStatement->execute();

        if ($preparedStatement->error) {
            var_export($preparedStatement->error);
            return false;
        }

        return true;
    }

    public function followUser($followerID, $followingID){
        $QUERY = "INSERT INTO follows (follower_id, following_id) VALUES (?, ?)";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("ii", $followerID, $followingID);

        return $preparedStatement->execute();
    }

    public function unfollowUser($followerID, $followingID) {
        $QUERY = "DELETE FROM follows WHERE follower_id=? AND following_id= ?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("ii", $followerID, $followingID);

        return $preparedStatement->execute();
    }

    public function getFollowersCount(int $followerID) {
        $QUERY = "SELECT COUNT(1) as count FROM follows WHERE follower_id=?";
        
        $preparedStatement = $this->connection->prepare($QUERY);
        $preparedStatement->bind_param("i", $followerID);

        return $preparedStatement->execute();
    }
}

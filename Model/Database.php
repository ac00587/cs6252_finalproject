<?php
class Database {
    private $db;
    private $error_message;
    
    /**
     * Instantiates a new database object that connects
     * to the database
     */
    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=pl_service';
        $username = 'mgs_user';
        $password = 'pa55word';
        $this->error_message = '';
        try {
            $this->db = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();
        }
    }
    
    /**
     * Checks the login credentials
     * 
     * @param type $username
     * @param type $password
     * @return boolen - true if the specified password is valid for the 
     *              specified username
     */
    public function isValidUserLogin($username, $password) {
        $query = 'SELECT password FROM users
              WHERE userName = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        if ($row === false) {
            return false;
        }
        $hash = $row['password'];
        return password_verify($password, $hash);
    }
    
    /**
     * Retrieves the userinfo for the specified user
     * 
     * @param string $username
     * @return array - array of items for the specified username
     */
    public function getInfoForUser($username) {
        $query = 'SELECT * FROM userinfo
                  WHERE userName = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $info = $statement->fetch();
        $statement->closeCursor();
        return $info;
    }
    
    /**
     * Retrieves the userinfo for the specified user
     * 
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @return array - array of tasks for the specified username
     */
    public function updateInfo($user_name, $first_name, $last_name, $email) {
        $query = 'UPDATE userinfo
                 SET firstName = :firstName, lastName = :lastName, email = :email
                 WHERE userName = :userName';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();
    }
        
    /**
     * Checks the connection to the database
     *
     * @return boolean - true if a connection to the database has been established
     */
    public function isConnected() {
        return ($this->db != Null);
    }
    
    /**
     * Returns the error message
     * 
     * @return string - the error message
     */
    public function getErrorMessage() {
        return $this->error_message;
    }    
    
}
?>
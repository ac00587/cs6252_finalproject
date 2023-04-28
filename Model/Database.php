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
     * Update info for specified user
     * 
     * @param string $user_name 
     * @param string $first_name
     * @param string $last_name
     * @param string $email
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
     * Adds an order for the specified user
     * 
     * @param string $user_name
     * @param string $char_name
     * @param string $char_server
     * @param string $power_leveler 
     * @param int $cost
     */
    public function addOrder($user_name, $char_name, $char_server, $power_leveler, $cost) {
        
        $query = 'INSERT INTO orders
                    (orderID, userName, dateOrdered, charName, charServer, powerLeveler, cost)
                 VALUES
                    (DEFAULT, :userName, NOW(), :charName, :charServer, :powerLeveler, :cost)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':charName', $char_name);
        $statement->bindValue(':charServer', $char_server);
        $statement->bindValue(':cost', $cost);
        $statement->bindValue(':userName', $user_name);
        $statement->bindValue(':powerLeveler', $power_leveler);
        $statement->execute();
        $statement->closeCursor();
    }   
    
    /**
     * Adds a new user
     * 
     * @param string $user_name     *  
     * @param string $password
     */
    public function addUser($user_name, $password) {
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = 'INSERT INTO users
                    (userID, userName, password)
                 VALUES
                    (DEFAULT, :userName, :password)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->bindValue(':password', $new_password);
        $statement->execute();
        $statement->closeCursor();
    }   
    
    /**
     * Adds a new user
     * 
     * @param string $user_name     *  
     * @param string $password
     */
    public function addUserInfo($username, $first_name, $last_name, $email) {
        
        $query = 'INSERT INTO userinfo
                    (userName, firstName, lastName, email)
                 VALUES
                    (:userName, :firstName, :lastName, :email)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':userName', $username);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $statement->closeCursor();
    }   
    
    /**
     * Retrieves all orders from user
     * @param type $username
     * @return type array
     */
    public function getOrders($username) {
        $query = 'SELECT * FROM orders
                  WHERE username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $tasks = $statement->fetchAll();
        $statement->closeCursor();
        return $tasks;
    }
    
    /**
     * Retrieves power levelers
     * @return array
     */
    public function getLevelers() {
        $query = 'SELECT * FROM pler';
        $statement = $this->db->prepare($query);
        $statement->execute();
        $pl = $statement->fetchAll();
        $statement->closeCursor();
        return $pl;
    }
    
    /**
     * Retrieves server names
     * @return array
     */
    public function getLoc() {
        $query = 'SELECT * FROM loc';
        $statement = $this->db->prepare($query);
        $statement->execute();
        $locations = $statement->fetchAll();
        $statement->closeCursor();
        return $locations;
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
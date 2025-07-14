<?php
// This Auth class uses \PDO for database connections!
// Make sure to have the \PDO extension enabled in your PHP configuration.

namespace PatrykNamyslak;

class Auth{
    protected \PDO $database;
    protected string $table;
    /**
     * @param string $database
     * @param string $table : The table that holds the authentication credentials
     * @param string $host : Host for your database e.g localhost.
     * @param string $username : Username for your database.
     * @param string $password : Password for your database.
     * 
     */
    public function __construct(string $database, string $table, string $host, string $username, string $password){
        $dsn = "mysql:host=$host;dbname=$database;";
        try {
            // Create a new PDO instance
            $this->database = new \PDO($dsn, $username, $password);
            // Set error mode to exception for better error handling
            $this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // Set default fetch mode to associative array
            $this->database->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            // Set the table name
            $this->table = $table;
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    /**
     * @param string $username : Username for the user.
     * @param string $password : Password for the user.
     * @return bool : Returns true if login is successful, false otherwise.
     */
    public function login(){
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $this->database->prepare("SELECT * FROM `{$this->table}` WHERE `Username` = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['Password'])) {
                $this->setUserData($user);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    /**
     * @return bool : Returns true if registration is successful, false otherwise.
     */
    public function register(){
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $this->database->prepare("INSERT INTO {$this->table} (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            try {
                $stmt->execute();
                return true;
            } catch (\PDOException $e) {
                return false; // Registration failed
            }
        }
        return false; // No data to register
    }
    /**
     * @return bool : Returns true if logout is successful, false otherwise.
     */
    public static function logout(){
        session_start();
        session_unset();
        session_destroy();
        header('location: /');
        exit;
    }
    /**
     * @return bool : Returns true if the user is logged in, false otherwise.
     */
    public static function isLoggedIn(){
        session_start();
        return isset($_SESSION['loggedIn']);
    }
    /**
     * @return int|null : Returns the user ID if logged in, null otherwise.
     */
    public function getUserId(){
        session_start();
        return $_SESSION['User_ID'] ?? null;
    }
    /**
     * @return array|null : Returns user data if logged in, null otherwise.
     */
    public function getUserData(){
        session_start();
        if (isset($_SESSION['User_ID'])) {
            $stmt = $this->database->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['User_ID']);
            $stmt->execute();
            return $stmt->fetch();
        }
        return null;
    }
    /**
     * @param string $newPassword : The new password to set for the user.
     * @return bool : Returns true if the password change is successful, false otherwise.
     */
    public function changePassword(string $newPassword){
        session_start();
        if (isset($_SESSION['User_ID'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->database->prepare("UPDATE {$this->table} SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $_SESSION['User_ID']);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * @return bool : Returns true if the user is deleted successfully, false otherwise.
     */
    public function deleteUser(){
        session_start();
        if (isset($_SESSION['User_ID'])) {
            $stmt = $this->database->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['User_ID']);
            $result = $stmt->execute();
            if ($result) {
                session_unset();
                session_destroy();
            }
            return $result;
        }
        return false;
    }

    /**
     * @return \PDO : Returns the \PDO database connection.
     */
    public function getDatabaseConnection(): \PDO {
        return $this->database;
    }

    /**
     * Sets user data in the session.
     *
     * @param array $userData The user data to set in the session.
     */
    private function setUserData(array $userData) {
        session_start();
        $_SESSION['User_ID'] = $userData['User_ID'];
        $_SESSION['Username'] = $userData['Username'];
        $_SESSION['Email'] = $userData['Email'];
        $_SESSION['loggedIn'] = true;
        // Add any other user data you want to store in the session
        // For example, you can store roles or permissions if needed
    }
}


?>
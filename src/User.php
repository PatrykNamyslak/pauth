<?php
// This model is used for controlling the logged in user or just any user that is found in the given parameter options
namespace PatrykNamyslak;

include_once 'environment_variables.php';


use Exception;

/** User model for authentication and user management */
class User extends Auth{
    private string $username;
    private string $userID;
    private string $email;

    public static function instantiate(object $Database, ?string $username=NULL, ?string $userID=NULL, ?string $email=NULL): User|NULL{
        if (!($Database->connection instanceof \PDO)){
            throw new Exception('$Database->connection must be an instance of PDO. ' . var_dump($Database) . ' provided.');
        }
        // Column to query the database by
        $ColumnToQueryBy = match (true){
            isset($username) => 'Username',
            isset($userID) => 'User_ID',
            isset($email) => 'Email',
        };
        // Determine the search value based on the provided parameters
        $SearchValue = $username ?? $userID ?? $email;
        // Prepare the SQL query to fetch user details
        $query = "SELECT `Username`,`Email`,`User_ID` FROM `{$Database->table}` WHERE `{$ColumnToQueryBy}` = '{$SearchValue}';";
        // Execute the query
        $data = $Database->connection->query($query)->fetch();
        if ($data) {
            return new User($data);
        }else {
            // If no user is found, throw an exception
            return NULL;
            // throw new Exception("User not found.");
        }
    }
    protected function __construct($userData){
        $this->username = $userData['Username'];
        $this->userID = $userData['User_ID'];
        $this->email = $userData['Email'];
    }
    public function username(): string{
        return $this->username;
    }
    public function email(): string{
        return $this->email;
    }
    public function userID(): string{
        return $this->userID;
    }
    /**
     * This function will return a unique user id by checking that it does not already exist
     * @return string
     */
    protected static function generate_UserID(): string{
        while (true){
            $generated_UserID = generate_random_string(8);
            if (!(User::instantiate(Database: $GLOBALS['predefined_db_connection'] ,userID: $generated_UserID))){
                break;
            }
        }
        return $generated_UserID;
    }
}

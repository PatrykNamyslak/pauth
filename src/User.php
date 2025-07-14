<?php
// This model is used for controlling the logged in user or just any user that is found in the given parameter options
namespace PatrykNamyslak;

use Exception;

/** User model for authentication and user management */
class User extends Auth{
    private string $username;
    private string $userID;
    private string $email;

    function __construct(object $Database, ?string $username=NULL, ?string $userID=NULL, ?string $email=NULL){
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
        $query = "SELECT `Username`,`Email`,`User_ID` FROM {$Database->table} WHERE '{$ColumnToQueryBy}' = '{$SearchValue}';";
        // Execute the query
        $data = $Database->query($query);
        if ($data) {
            $this->username = $data['Username'];
            $this->userID = $data['User_ID'];
            $this->email = $data['Email'];
        } else {
            // If no user is found, throw an exception
            throw new Exception("User not found.");
        }
    }
}

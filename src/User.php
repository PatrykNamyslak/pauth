<?php
// This model is used for controlling the logged in user or just any user that is found in the given parameter options
namespace PatrykNamyslak;

/** User model for authentication and user management */
class User extends Auth{
    private string $username;
    private string $userID;
    private string $email;

    function __construct(?string $username=NULL, ?string $userID=NULL, ?string $email=NULL, Database $Database){
        // Column to query the database by
        $ColumnToQueryBy = match (true){
            isset($username) => 'Username',
            isset($userID) => 'User_ID',
            isset($email) => 'Email',
        };
        // Determine the search value based on the provided parameters
        $SearchValue = $username ?? $userID ?? $email;
        // Prepare the SQL query to fetch user details
        $query = "SELECT `Username`,`Email`,`User_ID` FROM {$Auth->table} WHERE '{$ColumnToQueryBy}' = '{$SearchValue}';";
        // Execute the query
        $result = $Database->query($query);
        if ($result) {
            $this->username = $result['Username'];
            $this->userID = $result['User_ID'];
            $this->email = $result['Email'];
        } else {
            // If no user is found, throw an exception
            throw new \Exception("User not found.");
        }
    }
}

<?php
// This model is used for controlling the logged in user or just any user that is found in the given paramter options
namespace PatrykNamyslak;


class User extends Auth{
    private string $username;
    private string $userID;
    private string $email;

    function __construct(?string $username=NULL, ?string $userID=NULL, ?string $email=NULL, Auth $Auth){
        // Column to query the database by
        $ColumnToQueryBy = match (true){
            isset($username) => 'Username',
            isset($userID) => 'User_ID',
            isset($email) => 'Email',
        };
        $SearchValue = match (true){
            isset($username) => $username,
            isset($userID) => $userID,
            isset($email) => $email,
        };
        $query = "SELECT `Username`,`Email`,`User_ID` FROM {$Auth->table} WHERE '{$ColumnToQueryBy}' = '{$SearchValue}';";
    }
}

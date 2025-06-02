<?php
// This Auth class uses PDO for database connections!

class Database{
    private
}


class Auth{
    private PDO $database;

    /**
     * @param string $database
     * @param string $table : The table that holds the authentication credentials
     * @param string $host : Host for your database e.g localhost.
     * 
     */
    public function __construct(string $database, string $table, string $host, string $username, string $password){

    }
    public function login(){}
}


?>
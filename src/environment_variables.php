<?php
use PatrykNamyslak\Database;
require_once 'autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_ENV) and $_ENV !== [] and isset($_ENV['PHP_AUTH_DB_NAME'])){
    $Predefined_DB_Connection = new Database(host: $_ENV['PHP_AUTH_DB_HOST'], database_name: $_ENV['PHP_AUTH_DB_NAME'], username: $_ENV['PHP_AUTH_DB_USERNAME'], password: $_ENV['PHP_AUTH_DB_PASSWORD']);
}else{
    throw new Exception("Looks like you have not setup your configuration! Go to " . __DIR__ . '/.env to set it up');
}
?>
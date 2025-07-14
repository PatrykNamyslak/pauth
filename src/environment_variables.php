<?php
use PatrykNamyslak\Database;
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (isset($_ENV) and $_ENV !== []){
    $Predefined_DB_Connection = new Database(host: $_ENV['DB_HOST'], database_name: $_ENV['DB_NAME'], username: $_ENV['DB_USERNAME'], password: $_ENV['DB_PASSWORD']);
}
?>
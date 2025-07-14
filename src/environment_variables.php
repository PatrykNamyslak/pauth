<?php
use PatrykNamyslak\Database;
require_once 'functions.php';
require_once 'autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


/**
 * Set a default database connection connection if the configuration is setup properly
 */
$required_db_variables = [
    'PHP_AUTH_DB_HOST',
    'PHP_AUTH_DB_NAME',
    'PHP_AUTH_DB_USERNAME',
    'PHP_AUTH_DB_PASSWORD'
];

$requirements_met = TRUE;
foreach ($required_db_variables as $var){
    if (!isset($_ENV[$var]) and !empty($_ENV[$var])){
        $requirements_met = FALSE;
    }
}
if ($requirements_met){
    $predefined_db_connection = new Database(
        host: $_ENV['PHP_AUTH_DB_HOST'],
        database_name: $_ENV['PHP_AUTH_DB_NAME'],
        username: $_ENV['PHP_AUTH_DB_USERNAME'],
        password: $_ENV['PHP_AUTH_DB_PASSWORD']
    );
}else{
    throw new Exception("Looks like you have not setup your configuration! Go to " . __DIR__ . '/.env to set it up');
}

?>
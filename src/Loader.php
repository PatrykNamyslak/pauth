<?php
namespace PatrykNamyslak;
require_once 'environment_variables.php';

class Loader{
    private function __construct(){}
    public static function createInstance(){
        return new self;
    }
    public function load(){
        self::setPredefinedDatabaseObject();
    }
    private static function setPredefinedDatabaseObject(){
        global $predefined_db_connection;
        /**
         * Set a default database connection connection if the configuration is setup properly
        */
        $required_db_variables = [
            'PAUTH_DB_HOST',
            'PAUTH_DB_NAME',
            'PAUTH_DB_USERNAME',
            'PAUTH_DB_PASSWORD'
        ];

        $requirements_met = TRUE;
        foreach ($required_db_variables as $var){
            if (!isset($_ENV[$var]) and !empty($_ENV[$var])){
                $requirements_met = FALSE;
            }
        }
        if ($requirements_met){
            $predefined_db_connection = new Patbase(
                host: $_ENV['PAUTH_DB_HOST'],
                database_name: $_ENV['PAUTH_DB_NAME'],
                username: $_ENV['PAUTH_DB_USERNAME'],
                password: $_ENV['PAUTH_DB_PASSWORD']
            );
        }else{
            throw new \Exception("Looks like you have not setup your configuration! Go to " . __DIR__ . '/.env to set it up');
        }
    }
}

?>
<?php
namespace PatrykNamyslak;
/**
 * * Database class for managing database connections and queries
 */
class Database {
    private \PDO $connection;
    public string $table;
    /**
     * @param string $host : Host for your database e.g localhost.
     * @param string $database_name : Name of the database.
     * @param string $username : Username for your database.
     * @param string $password : Password for your database.
     */
    
    // Constructor to initialize the database connection
    // and set the default table name
    // Default table is 'users' if not specified
    public function __construct(string $host='localhost', string $database_name, string $username, string $password, string $table = 'users') {
        // Default table
        $this->table = $table;
        // Create a new PDO connection
        $this->connection = new \PDO("mysql:host={$host};dbname={$database_name}", $username, $password);
    }
    // Query the database and return results
    public function query(string $sql): array {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    // Execute a prepared statement
    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }
    public function connection(): \PDO {
        return $this->connection;
    }
}

?>
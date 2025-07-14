<?php
namespace PatrykNamyslak;

class Database {
    private \PDO $connection;
    public string $table;
    /**
     * @param string $host : Host for your database e.g localhost.
     * @param string $dbname : Name of the database.
     * @param string $username : Username for your database.
     * @param string $password : Password for your database.
     */
    public function __construct(string $host='localhost', string $dbname, string $username, string $password, string $table = 'users') {
        // Default table
        $this->table = $table;
        // Create a new PDO connection
        $this->connection = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }

    public function query(string $sql): array {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }
    public function connection(): \PDO {
        return $this->connection;
    }
}

?>
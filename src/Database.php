<?php
namespace PlanItOut;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private PDO $connection;
    
    private function __construct() {
        // Get database connection details from environment variables
        $host = getenv('PGHOST');
        $port = getenv('PGPORT');
        $dbname= getenv('PGDATABASE');
        $username = getenv('PGUSER'); 
        $password = getenv('PGPASSWORD');

        $dsn = "pgsql:host=${host};port=${port};dbname=${dbname}";

        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo '<p>Successfully connected to the database!</p>';

            // Example query
            $stmt = $this->connection->query('SELECT current_timestamp');
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<p>Current database time: ' . $result['current_timestamp'] . '</p>';

            // Check if test table exists first
            $tablesQuery = $this->connection->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
            $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

            echo "<p>Available tables:<br>";
            foreach ($tables as $table) {
                echo "- $table -";
            }
            echo "</p>";
        } catch (PDOException $e) {
            echo '<p>Detailed connection error: ' . $e->getMessage() . '</p>';
        }
    } 
    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    public function query(string $sql, array $params = []): \PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchAll(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insert(string $table, array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, array_values($data));
        
        return $this->connection->lastInsertId();
    }
    
    public function update(string $table, array $data, string $where, array $whereParams = []): int {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "$column = ?";
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $where";
        $params = array_merge(array_values($data), $whereParams);
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function delete(string $table, string $where, array $params = []): int {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
}

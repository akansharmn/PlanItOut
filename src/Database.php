
<?php

namespace PlanItOut;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private PDO $connection;
    
    private function __construct() {
        $db_url = 'postgresql://neondb_owner:npg_UM6tP9EakcVi@ep-wild-frost-a6h1l00f.us-west-2.aws.neon.tech/neondb?sslmode=require';
        
        if (!$db_url) {
            throw new PDOException("No DATABASE_URL environment variable set");
        }
        
        try {
            // Parse the connection string to properly format it for PDO
            $db_parts = parse_url($db_url);
            $dsn = "pgsql:host={$db_parts['host']};dbname=" . ltrim($db_parts['path'], '/');
            
            // Add port if specified
            if (isset($db_parts['port'])) {
                $dsn .= ";port={$db_parts['port']}";
            }
            
            $username = $db_parts['user'];
            $password = $db_parts['pass'];
            
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage());
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


<?php
// Include debug settings
require_once 'debug.php';

echo '<h1>Hello World! from Akansha</h1>';

// Database connection
try {
    // Get database connection details from environment variables
    $db_url = getenv('DATABASE_URL');
    
    echo '<p>Database URL: ' . $db_url . '</p>';

    if ($db_url) {
        echo '<h2>Database Connection</h2>';
        echo '<p>Database URL is configured. You can connect to PostgreSQL.</p>';
        
        // Parse the connection string to properly format it for PDO
        if (strpos($db_url, 'postgresql://') === 0) {
            // The URL is in format: postgresql://username:password@hostname:port/database?options
            $db_parts = parse_url($db_url);
            $dsn = "pgsql:host={$db_parts['host']};port={$db_parts['port']};dbname=" . ltrim($db_parts['path'], '/');
            $username = $db_parts['user'];
            $password = $db_parts['pass'];
            
            echo "<p>Parsed connection info:<br>
                 Host: {$db_parts['host']}<br>
                 Port: {$db_parts['port']}<br>
                 Database: " . ltrim($db_parts['path'], '/') . "<br>
                 Username: {$username}</p>";
                 
            try {
                $dbConn = new PDO($dsn, $username, $password);
                $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo '<p>Successfully connected to the database!</p>';
                
                // Example query
                $stmt = $dbConn->query('SELECT current_timestamp');
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<p>Current database time: ' . $result['current_timestamp'] . '</p>';
                
                // Check if test table exists first
                $tablesQuery = $dbConn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
                $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<p>Available tables:<br>";
                foreach ($tables as $table) {
                    echo "- $table<br>";
                }
                echo "</p>";
                
                if (in_array('test', $tables)) {
                    $stmt1 = $dbConn->query('SELECT * FROM test');
                    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                    if ($result1) {
                        echo '<p>Current row: ' . $result1['name'] . '</p>';
                    } else {
                        echo '<p>No data found in test table</p>';
                    }
                } else {
                    echo '<p>Test table does not exist yet</p>';
                }
            } catch (PDOException $e) {
                echo '<p>Detailed connection error: ' . $e->getMessage() . '</p>';
            }
        } else {
            echo '<p>The DATABASE_URL format is not recognized. Expected format: postgresql://username:password@hostname:port/database</p>';
        }
    } else {
        echo '<p>No database configured yet. Create a PostgreSQL database from the Database tab.</p>';
    }
} catch (PDOException $e) {
    echo '<p>General database error: ' . $e->getMessage() . '</p>';
    echo '<p>Error code: ' . $e->getCode() . '</p>';
    echo '<pre>Stack trace: ' . $e->getTraceAsString() . '</pre>';
}

// Uncomment to display PHP configuration info
// echo '<h2>PHP Information</h2>';
// phpinfo();
?>

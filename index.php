
<?php
echo '<h1>Hello World! from Akansha</h1>';

// Database connection
try {
    // Get database connection details from environment variables
    $db_url = getenv('DATABASE_URL');

    if ($db_url) {
        echo '<h2>Database Connection</h2>';
        echo '<p>Database URL is configured. You can connect to PostgreSQL.</p>';

        // Example connection (commented out until database is created)
        
        $dbConn = new PDO($db_url);
        $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo '<p>Successfully connected to the database!</p>';

        // Example query
        $stmt = $dbConn->query('SELECT current_timestamp');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<p>Current database time: ' . $result['current_timestamp'] . '</p>';

        $stmt1 = $dbConn->query('SLECET * from test');
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        echo '<p>Current row: ' . $result1['name'] . '</p>';
        
    } else {
        echo '<p>No database configured yet. Create a PostgreSQL database from the Database tab.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Database connection error: ' . $e->getMessage() . '</p>';
}

// Display PHP info
// phpinfo();
?>

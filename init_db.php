
<?php
// Main script to initialize database

// Include debug file for error reporting
require_once 'debug.php';

// Run the initialization script
require_once 'src/db_scripts/init_database.php';

echo "<p>Database initialization process completed.</p>";
echo "<p><a href='index.php'>Go back to main page</a></p>";

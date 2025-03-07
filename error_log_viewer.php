
<?php
// View recent PHP error logs
require_once 'debug.php';

// Get error log location
$error_log_path = ini_get('error_log');
echo "<h2>PHP Error Log Location: " . ($error_log_path ?: "Not configured") . "</h2>";

// Function to get the last n lines of a file
function tail($file, $lines = 50) {
    $file = fopen($file, 'r');
    $total_lines = count(file($file->getPathname()));
    $start_line = max(0, $total_lines - $lines);
    
    $result = [];
    $current_line = 0;
    
    while (($line = fgets($file)) !== false) {
        if ($current_line >= $start_line) {
            $result[] = $line;
        }
        $current_line++;
    }
    
    fclose($file);
    return $result;
}

// Display error log contents if it exists
if ($error_log_path && file_exists($error_log_path)) {
    echo "<h3>Last 50 log entries:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 500px; overflow-y: auto;'>";
    $log_lines = tail($error_log_path);
    foreach ($log_lines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p>Error log file doesn't exist or is not accessible.</p>";
}

// Display errors logged with error_log() function
echo "<h3>Custom Error Log Function</h3>";
echo "<p>You can also use PHP's <code>error_log()</code> function in your code:</p>";
echo "<pre>error_log('Your custom error message');</pre>";
?>

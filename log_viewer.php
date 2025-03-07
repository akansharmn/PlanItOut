
<?php
require_once 'vendor/autoload.php';
use PlanItOut\Logger;

echo "<h1>Application Log Viewer</h1>";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 500px; overflow-y: auto;'>";
echo htmlspecialchars(Logger::viewLogs());
echo "</pre>";

// Example of how to use the logger
echo "<h2>Using the Custom Logger</h2>";
echo "<p>Add this to your code to log errors:</p>";
echo "<pre>
use PlanItOut\Logger;

// Log errors
Logger::error('Something went wrong');

// Log info messages
Logger::info('User logged in');

// Log debug messages
Logger::debug('Variable value: ' . \$someVar);
</pre>";
?>

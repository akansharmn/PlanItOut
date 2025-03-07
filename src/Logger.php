
<?php
namespace PlanItOut;

class Logger {
    private static string $logFile = 'app_errors.log';
    
    public static function log($message, $type = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] [$type] $message" . PHP_EOL;
        
        file_put_contents(self::$logFile, $formattedMessage, FILE_APPEND);
    }
    
    public static function error($message) {
        self::log($message, 'ERROR');
    }
    
    public static function info($message) {
        self::log($message, 'INFO');
    }
    
    public static function debug($message) {
        self::log($message, 'DEBUG');
    }
    
    public static function viewLogs($lines = 50) {
        if (!file_exists(self::$logFile)) {
            return "No logs available.";
        }
        
        $logs = file(self::$logFile);
        $totalLines = count($logs);
        $logsToShow = array_slice($logs, max(0, $totalLines - $lines));
        
        return implode('', $logsToShow);
    }
}

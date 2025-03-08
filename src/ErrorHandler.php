<?php
namespace PlanItOut;

class ErrorHandler {
    public static function register() {
        // Custom error handler
        set_error_handler([self::class, 'handleError']);
        
        // Custom exception handler
        set_exception_handler([self::class, 'handleException']);
    }
    
    public static function handleError($errno, $errstr, $errfile, $errline) {
        // Log the error
        Logger::error("PHP Error: [$errno] $errstr in $errfile on line $errline");
        
        // Don't display warnings and notices to the user
        if ($errno == E_WARNING || $errno == E_NOTICE || $errno == E_DEPRECATED) {
            return true; // Prevent PHP from handling this error
        }
        
        // For critical errors, we can show a user-friendly message
        if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::displayFriendlyError();
            exit(1);
        }
        
        return false; // Let PHP handle other errors
    }
    
    public static function handleException($exception) {
        // Log the exception
        Logger::error("Uncaught Exception: " . $exception->getMessage() . 
                      " in " . $exception->getFile() . 
                      " on line " . $exception->getLine());
        
        // Display a user-friendly error page
        self::displayFriendlyError();
        exit(1);
    }
    
    private static function displayFriendlyError() {
        // Clear any output that might have been sent
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Only display generic error in production
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h1>Something went wrong</h1>';
        echo '<p>We\'re sorry, but there was an error processing your request.</p>';
        echo '<p><a href="/home">Return to home page</a></p>';
        echo '</div>';
    }
}

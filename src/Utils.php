<?php
namespace PlanItOut;

class Utils {
    /**
     * Safely redirect to another page
     * 
     * @param string $url The URL to redirect to
     * @return void
     */
    public static function safeRedirect($url) {
        // Clear any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Check if headers have already been sent
        if (headers_sent()) {
            // Use JavaScript fallback for redirection if headers already sent
            echo '<script>window.location.href="' . htmlspecialchars($url) . '";</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url) . '">';
            echo '</noscript>';
            echo '<p>Redirecting to <a href="' . htmlspecialchars($url) . '">next page</a>...</p>';
            exit;
        }
        
        // Use standard header redirect
        header('Location: ' . $url);
        exit;
    }
}
?>

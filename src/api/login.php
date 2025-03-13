<?php
namespace PlanItOut\Api;
use PlanItOut\Logger;

require_once 'src/auth/AuthManager.php';
use PlanItOut\Auth\AuthManager;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

Logger::debug("entered login.php");
Logger::debug('HTMX request: ' . ($isHtmxRequest ? 'yes' : 'no'));
Logger::debug($_SERVER['REQUEST_METHOD'] . ' request received');

// If not an HTMX request, include the header
if (!$isHtmxRequest) {
    include 'src/templates/header.php';
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $auth = AuthManager::getInstance();
    
    if ($auth->login($username, $password)) {
        // Successful login - session is already set by the AuthManager
        if ($isHtmxRequest) {
            // Return success message before redirecting
            echo '<div id="form-response" class="alert alert-success mt-3" role="alert">';
            echo '<i class="bi bi-check-circle-fill me-2"></i>';
            echo 'Login successful! Redirecting...';
            echo '</div>';
            
            // Return htmx redirect
            header('HX-Redirect: /home');
            exit;
        } else {
            // For non-HTMX requests, just redirect
            header('Location: /home');
            exit;
        }
    } else {
        // Failed login
        if ($isHtmxRequest) {
            // Return HTML error response for HTMX
            echo '<div id="form-response" class="alert alert-danger mt-3" role="alert">';
            echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
            echo 'Invalid username or password';
            echo '</div>';
            
            // Add a trigger to reset the form - proper JSON format
            header('HX-Trigger: {"resetForm": true}');
        } else {
            // For non-HTMX requests, set error variable to be displayed in template
            $error = 'Invalid username or password';
        }
    }
}  else {
    // Include the registration form template
    Logger::debug('entered non-post method section');
    include 'src/api/login.htmx';
}



// If not an HTMX request, include the footer
if (!$isHtmxRequest) {
    include 'src/templates/footer.php';
}

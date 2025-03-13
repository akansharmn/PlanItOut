
<?php
namespace PlanItOut\Api;

require_once 'src/auth/AuthManager.php';
use PlanItOut\Auth\AuthManager;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $auth = AuthManager::getInstance();
    
    if ($auth->login($username, $password)) {
        // Successful login
        if ($isHtmxRequest) {
            // Return htmx redirect
            header('HX-Redirect: /home');
            exit;
        } else {
            header('Location: /home');
            exit;
        }
    } else {
        // Failed login
        $error = 'Invalid username or password';
    }
}

// If not an HTMX request, include the header
if (!$isHtmxRequest) {
    include 'src/templates/header.php';
}

// Include the HTMX template
include 'src/api/login.htmx';

// If not an HTMX request, include the footer
if (!$isHtmxRequest) {
    include 'src/templates/footer.php';
}

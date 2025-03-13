<?php
namespace PlanItOut\Api;

require_once 'src/auth/AuthManager.php';
use PlanItOut\Auth\AuthManager;
use PlanItOut\Utils;
use PlanItOut\Logger;

// Get auth manager
$auth = AuthManager::getInstance();

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    Logger::debug('User not logged in, cannot logout');
    
    // If HTMX request, redirect via HX-Redirect
    if (isset($_SERVER['HTTP_HX_REQUEST'])) {
        header('HX-Redirect: /login');
        exit;
    } else {
        // Standard redirect for regular requests
        Utils::safeRedirect('/login');
    }
}

// Log out the user
$auth->logout();

// Check if it's an HTMX request
if (isset($_SERVER['HTTP_HX_REQUEST'])) {
    // Return successful logout message
    echo '<div class="alert alert-success" role="alert">';
    echo 'You have been logged out successfully.';
    echo '</div>';
    
    // Redirect to login
    header('HX-Redirect: /login');
} else {
    // For non-HTMX requests, redirect to login page
    Utils::safeRedirect('/login');
}
exit;

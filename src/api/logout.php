
<?php
namespace PlanItOut\Api;

require_once 'src/auth/AuthManager.php';
use PlanItOut\Auth\AuthManager;

// Get auth manager
$auth = AuthManager::getInstance();

// Logout user
$auth->logout();

// Redirect to login page
header('Location: /login');
exit;

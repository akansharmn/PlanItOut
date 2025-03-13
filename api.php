<?php
namespace PlanItOut;

// Start output buffering
ob_start();

require_once 'vendor/autoload.php';
require_once 'debug.php';
require_once 'src/ErrorHandler.php';
require_once 'src/auth/AuthManager.php';
require_once 'src/Utils.php';

// Register error handlers to prevent warnings from showing in the UI
ErrorHandler::register();

// Simple router for API endpoints
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Remove leading slash and get endpoint
$endpoint = ltrim($path, '/');
Logger::debug('New request: Endpoint: ' . $endpoint);

// Initialize auth manager
$auth = Auth\AuthManager::getInstance();

// Define public endpoints that don't require authentication
$publicEndpoints = ['login', 'register', 'health', 'home',''];

// Check if authentication is required for this endpoint
$requireAuth = !in_array($endpoint, $publicEndpoints);

// If authentication is required and user is not logged in
if ($requireAuth && !$auth->isLoggedIn()) {
    Logger::debug('Authentication required for endpoint: ' . $endpoint);
    
    // If HTMX request, redirect via HX-Redirect
    if (isset($_SERVER['HTTP_HX_REQUEST'])) {
        header('HX-Redirect: /login');
        exit;
    } else {
        // Standard redirect for regular requests
        Utils::safeRedirect('/login');
    }
}

switch ($endpoint) {
    case 'health':
        require_once 'src/api/health.php';
        break;

    case 'loadRecipe':  // Keep for backward compatibility
    case 'createRecipe':
        require_once 'src/api/createRecipe.php';
        break;

    case 'createRecipePage':
        require_once 'src/api/createRecipePage.php';
        break;

    case 'createMealPreference':
        require_once 'src/api/createMealPreference.php';
        break;

    // HTMX endpoints
    case 'recipes':
        require_once 'src/api/recipes.php';
        break;

    case 'recipe-details':
        require_once 'src/api/recipe-details.php';
        break;

    case 'home':
        require_once 'src/api/home.php';
        break;
    case 'createMealPreferencePage':
        require_once 'src/api/createMealPreferencePage.php';
        break;

    case 'register':
        require_once 'src/api/register.php';
        break;
    case 'login':
        require_once 'src/api/login.php';
        break;
    case 'logout':
        require_once 'src/api/logout.php';
        break;
    // Index page - redirect to home
    case '':
        require_once 'src/Utils.php';
        Utils::safeRedirect('/home');
        break;

    default:
        // Return 404 for unknown endpoints
        if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            header('Content-Type: application/json');
            header('HTTP/1.1 404 Not Found');
            echo json_encode([
                'status' => 'error',
                'message' => 'Endpoint not found'
            ]);
        } else {
            header('HTTP/1.1 404 Not Found');
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The requested page does not exist.</p>';
            echo '<p><a href="/recipes">Go to Recipes</a></p>';
        }
}
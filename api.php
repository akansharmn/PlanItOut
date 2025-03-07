<?php
namespace PlanItOut;

require_once 'vendor/autoload.php';
require_once 'debug.php';

// Simple router for API endpoints
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Remove leading slash and get endpoint
$endpoint = ltrim($path, '/');
// Print the value of uri, path, and endpoint
error_log('URI: ' . $uri);
error_log('Path: ' . $path);
error_log('Endpoint: ' . $endpoint);
switch ($endpoint) {
    case 'health':
        require_once 'src/api/health.php';
        break;
        
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
        
    // Index page - redirect to home
    case '':
        header('Location: /home'); 
        exit;
        
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

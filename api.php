<?php
namespace PlanItOut;

require_once 'vendor/autoload.php';
require_once 'debug.php';

// Simple router for API endpoints
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Remove leading slash and get endpoint
$endpoint = ltrim($path, '/');

switch ($endpoint) {
    case 'health':
        require_once 'src/api/health.php';
        break;
        
    case 'createRecipe':
        require_once 'src/api/createRecipe.php';
        break;
        
    default:
        // Return 404 for unknown endpoints
        header('HTTP/1.1 404 Not Found');
        echo json_encode([
            'status' => 'error',
            'message' => 'Endpoint not found'
        ]);
}

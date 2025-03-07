<?php
namespace PlanItOut\Api;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../debug.php';

use PlanItOut\Database;
use PDOException;

// Set content type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

// Check if data is coming from a form or as JSON
if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Get JSON data from the request body
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
} else {
    // Get form data
    $data = $_POST;
}

// Validate input data
if (!$data || !isset($data['recipeName']) || !isset($data['ingredients']) || !isset($data['prePreparations'])) {
    http_response_code(400); // Bad Request
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields: recipeName, ingredients, or prePreparations'
    ]);
    exit;
}

try {
    // Get database connection
    $db = Database::getInstance();
    
    // Insert recipe into database
    $recipeId = $db->insert('recipes', [
        'recipe_name' => $data['recipeName'],
        'ingredients' => $data['ingredients'],
        'pre_preparations' => $data['prePreparations']
    ]);
    
    // Return success response
    http_response_code(201); // Created
    echo json_encode([
        'status' => 'success',
        'message' => 'Recipe created successfully',
        'data' => [
            'id' => $recipeId,
            'recipeName' => $data['recipeName']
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to create recipe: ' . $e->getMessage()
    ]);
}
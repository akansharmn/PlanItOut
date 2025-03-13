<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;
use PlanItOut\Logger;

header('Content-Type: application/json');
Logger::debug("entered createMealPreference.php");

Logger::debug($_SERVER['REQUEST_METHOD'] . ' request received');
Logger::debug('input is:'.  file_get_contents('php://input') );

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Use POST instead.'
    ]);
    exit;
}

// Get JSON data from the request body
//$jsonData = file_get_contents('php://input'); 
//$data = json_decode($jsonData, true);


// Check if data is coming from a form or as JSON
if ($_SERVER['CONTENT_TYPE'] && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Get JSON data from the request body
    $jsonData = file_get_contents('php://input');
    Logger::debug($jsonData);
    $data = json_decode($jsonData, true);
} else {
    // Get form data
    $data = $_POST;
}
Logger::debug('data is:'.  $data );
Logger::debug('is mealType set?:'.isset($data['mealType']));
Logger::debug('is recipeIdSet?:'.isset($data['recipeId']));    

// Validate input data
if (!$data || !isset($data['mealType']) || empty($data['mealType'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required field: mealType'
    ]);
    exit;
}
Logger::debug('mealType is set to '.$data['mealType']);
// We need either recipeName or recipeId
if ((!isset($data['recipeName']) || empty($data['recipeName'])) && 
    (!isset($data['recipeId']) || !is_numeric($data['recipeId']))) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'You must provide either recipeName or recipeId'
    ]);
    exit;
}

Logger::debug('recipeId is set to:'. $data['recipeId']);

try {
    $db = Database::getInstance();
    
    // Get meal type ID
    $mealType = strtoupper($data['mealType']);
    $mealTypeResult = $db->fetch("SELECT id FROM meal_types WHERE name = ?", [$mealType]);
    
    if (!$mealTypeResult) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => "Invalid meal type: {$data['mealType']}. Valid types are: BREAKFAST, LUNCH, DINNER"
        ]);
        exit;
    }
    
    $mealTypeId = $mealTypeResult['id'];
    $recipeId = null;
    
    // If recipeId is provided, check if it exists
    if (isset($data['recipeId']) && is_numeric($data['recipeId'])) {
        $recipeResult = $db->fetch("SELECT id FROM recipes WHERE id = ?", [$data['recipeId']]);
        
        if (!$recipeResult) {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => "Recipe with ID {$data['recipeId']} not found"
            ]);
            exit;
        }
        
        $recipeId = $data['recipeId'];
    } 
    // If recipeName is provided, check if it exists or create it
    else if (isset($data['recipeName']) && !empty($data['recipeName'])) {
        $recipeResult = $db->fetch("SELECT id FROM recipes WHERE recipe_name = ?", [$data['recipeName']]);
        
        if ($recipeResult) {
            $recipeId = $recipeResult['id'];
        } else {
            // Create a new recipe
            $recipeId = $db->insert('recipes', [
                'recipe_name' => $data['recipeName'],
                'ingredients' => $data['ingredients'] ?? '',
                'pre_preparations' => $data['prePreparations'] ?? ''
            ]);
        }
    }
    
    // Check if the meal preference already exists
    $existingPreference = $db->fetch(
        "SELECT id FROM meal_preferences WHERE meal_type_id = ? AND recipe_id = ?", 
        [$mealTypeId, $recipeId]
    );
    
    if ($existingPreference) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Meal preference already exists',
            'data' => [
                'id' => $existingPreference['id'],
                'mealTypeId' => $mealTypeId,
                'recipeId' => $recipeId
            ]
        ]);
        exit;
    }
    
    // Insert meal preference
    $preferenceId = $db->insert('meal_preferences', [
        'meal_type_id' => $mealTypeId,
        'recipe_id' => $recipeId
    ]);
    
    // Get recipe details for the response
    $recipe = $db->fetch("SELECT * FROM recipes WHERE id = ?", [$recipeId]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Meal preference created successfully',
        'data' => [
            'id' => $preferenceId,
            'mealType' => $mealType,
            'recipe' => [
                'id' => $recipe['id'],
                'name' => $recipe['recipe_name'],
                'ingredients' => $recipe['ingredients'],
                'prePreparations' => $recipe['pre_preparations']
            ]
        ]
    ]);
    
} catch (\Exception $e) {
    http_response_code(200);
    Logger::debug('exception is'.$e);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

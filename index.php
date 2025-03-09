<?php
namespace PlanItOut;
// Include necessary files

require_once 'debug.php';
require_once 'src/Database.php'; // Include the Database class file


// Handle routing based on path
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Basic router
switch ($path) {
    case '/':
    case '/home':
        include 'src/api/home.php';
        break;
    case '/createRecipePage':
        include 'src/api/createRecipePage.php';
        break;
    case '/createMealPreferencePage':
        include 'src/api/createMealPreferencePage.php';
        break;
    case '/createRecipe':
        include 'src/api/createRecipe.php';
        break;
    case '/createMealPreference':
        include 'src/api/createMealPreference.php';
        break;
    case '/recipes':
        include 'src/api/recipes.php';
        break;
    default:
        http_response_code(404);
        echo '<h1>Page Not Found</h1>';
        break;
}

// Database connection
// $db = new Database()
$db = Database::getInstance();

// Rest of the code remains the same...
$db->query("
        CREATE TABLE IF NOT EXISTS recipes (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            ingredients TEXT,
            prerequisites TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
$recipeId = $db->insert('recipes', [
    'name' => 'Pancakes',
    'ingredients' => 'Flour, Eggs, Milk, Sugar, Baking Powder',
    'prerequisites' => 'Mix all ingredients until smooth'
]);

echo "Inserted recipe with ID: $recipeId\n";

// Fetch all recipes
$recipes = $db->fetchAll("SELECT * FROM recipes");

echo "All recipes:\n";
foreach ($recipes as $recipe) {
    echo "- {$recipe['name']}: {$recipe['ingredients']}\n";
}
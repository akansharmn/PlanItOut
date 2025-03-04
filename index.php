namespace PlanItOut;

<?php


// Include necessary files

require_once 'debug.php';
require_once 'src/Database.php'; // Include the Database class file


echo '<h1>Hello World! from Akansha</h1>';

// Database connection
$db = new Database();
$conn = $db->getInstance()->getConnection();

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
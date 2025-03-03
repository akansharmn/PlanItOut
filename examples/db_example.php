
<?php

require_once __DIR__ . '/../src/Database.php';

use PlanItOut\Database;

try {
    $db = Database::getInstance();
    
    // Create a test table if it doesn't exist
    $db->query("
        CREATE TABLE IF NOT EXISTS recipes (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            ingredients TEXT,
            prerequisites TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Insert a record
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
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

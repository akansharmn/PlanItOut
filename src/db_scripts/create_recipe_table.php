
<?php

namespace PlanItOut;

require_once __DIR__ . '/../../vendor/autoload.php';
use PlanItOut\Database;

try {
    // Get the database connection
    $db = Database::getInstance();
    
    echo "Creating Recipe table...\n";
    
    // Create Recipe table if it doesn't exist
    $db->query("
        CREATE TABLE IF NOT EXISTS recipes (
            id SERIAL PRIMARY KEY,
            recipe_name VARCHAR(255) NOT NULL,
            ingredients TEXT NOT NULL,
            prerequisites TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Recipe table created successfully!\n";
    
    // Insert some example data
    $recipeId1 = $db->insert('recipes', [
        'recipe_name' => 'Spaghetti Carbonara',
        'ingredients' => 'Pasta, Eggs, Bacon, Parmesan Cheese, Black Pepper',
        'prerequisites' => 'Cook pasta al dente, crisp bacon before mixing'
    ]);
    
    $recipeId2 = $db->insert('recipes', [
        'recipe_name' => 'Chicken Curry',
        'ingredients' => 'Chicken, Onions, Curry Powder, Coconut Milk, Garlic, Ginger',
        'prerequisites' => 'Marinate chicken for at least 1 hour'
    ]);
    
    echo "Added example recipes with IDs: $recipeId1, $recipeId2\n";
    
    // List all recipes
    $recipes = $db->fetchAll("SELECT * FROM recipes");
    
    echo "\nCurrent recipes in database:\n";
    foreach ($recipes as $recipe) {
        echo "- {$recipe['recipe_name']}: {$recipe['ingredients']}\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

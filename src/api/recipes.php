<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;

// Check if it's an HTMX request that wants only the recipe list
$wantPartial = isset($_SERVER['HTTP_HX_REQUEST']);

// If not a partial request, include the header
if (!$wantPartial) {
    include 'src/templates/header.php';
    echo '<h1>Recipes</h1>';
}

try {
    $db = Database::getInstance();
    $recipes = $db->fetchAll("SELECT * FROM recipes ORDER BY recipe_name");

    // Output the recipe list
    echo '<div id="recipe-list">';
    
    if (count($recipes) > 0) {
        echo '<ul>';
        foreach ($recipes as $recipe) {
            echo '<li>';
            echo '<strong>' . htmlspecialchars($recipe['recipe_name']) . '</strong>';
            echo ' <button class="btn btn-sm" hx-get="/recipe-details?id=' . $recipe['id'] . '" ';
            echo 'hx-target="#recipe-details">View Details</button>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No recipes found.</p>';
    }
    
    echo '</div>';
    
    // Only show the details section if not a partial request
    if (!$wantPartial) {
        echo '<div id="recipe-details"></div>';
    }
} catch (\Exception $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

// If not a partial request, include the footer
if (!$wantPartial) {
    include 'src/templates/footer.php';
}

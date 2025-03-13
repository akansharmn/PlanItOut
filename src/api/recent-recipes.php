
<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

try {
    $db = Database::getInstance();
    
    // Fetch the most recent recipes (limit to 5)
    $recipes = $db->fetchAll("SELECT * FROM recipes ORDER BY id DESC LIMIT 5");
    
    if (count($recipes) > 0) {
        echo '<div class="list-group">';
        foreach ($recipes as $recipe) {
            echo '<a href="/recipe-details?id=' . $recipe['id'] . '" class="list-group-item list-group-item-action">';
            echo '<div class="d-flex w-100 justify-content-between">';
            echo '<h5 class="mb-1">' . htmlspecialchars($recipe['recipe_name']) . '</h5>';
            echo '</div>';
            echo '<small class="text-muted">Prep time: ' . htmlspecialchars($recipe['prep_time'] ?? 'N/A') . '</small>';
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="text-center p-3">';
        echo '<p class="mb-0">No recipes found. <a href="/createRecipePage">Add your first recipe</a>!</p>';
        echo '</div>';
    }
} catch (\Exception $e) {
    echo '<div class="alert alert-danger">Error loading recipes: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>

<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

// If not an HTMX request, include the header
if (!$isHtmxRequest) {
    include 'src/templates/header.php';
}

try {
    $db = Database::getInstance();
    
    // Get all meal types
    $mealTypes = $db->fetchAll("SELECT id, name FROM meal_types ORDER BY id");
    
    // Get all recipes
    $recipes = $db->fetchAll("SELECT id, recipe_name FROM recipes ORDER BY recipe_name");
    
    // Include the HTMX template
    include 'src/api/createMealPreferencePage.htmx';
    
} catch (\Exception $e) {
    echo '<div class="alert alert-danger">';
    echo 'Error: ' . htmlspecialchars($e->getMessage());
    echo '</div>';
}

// If not an HTMX request, include the footer
if (!$isHtmxRequest) {
    include 'src/templates/footer.php';
}
?>

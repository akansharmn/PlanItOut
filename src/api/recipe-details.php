
<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;
require_once 'src/auth/AuthManager.php';
use PlanItOut\Auth\AuthManager;

// Check if user is authenticated
$auth = AuthManager::getInstance();
$isAuthenticated = $auth->isLoggedIn();

if (!$isAuthenticated) {
    echo '<div class="alert alert-warning">';
    echo '<p>Please <a href="/login">log in</a> to view recipe details.</p>';
    echo '</div>';
    exit;
}

// Get recipe ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo '<p>Invalid recipe ID</p>';
    exit;
}

try {
    $db = Database::getInstance();
    $recipe = $db->fetch("SELECT * FROM recipes WHERE id = ?", [$id]);
    
    if (!$recipe) {
        echo '<p>Recipe not found</p>';
        exit;
    }
    
    // Output recipe details
    echo '<div class="recipe-details-card">';
    echo '<h2>' . htmlspecialchars($recipe['recipe_name']) . '</h2>';
    
    echo '<h3>Ingredients:</h3>';
    echo '<p>' . nl2br(htmlspecialchars($recipe['ingredients'])) . '</p>';
    
    echo '<h3>Preparation:</h3>';
    echo '<p>' . nl2br(htmlspecialchars($recipe['pre_preparations'])) . '</p>';
    
    echo '<button hx-get="/recipes" hx-target="#recipe-details" hx-swap="outerHTML">Close</button>';
    echo '</div>';
    
} catch (\Exception $e) {
    echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

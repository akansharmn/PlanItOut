
<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
use PlanItOut\Database;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

try {
    $db = Database::getInstance();
    
    // This is a placeholder until meal planning is implemented
    echo '<div class="text-center p-3">';
    echo '<p class="mb-0">No upcoming meals planned yet.</p>';
    echo '</div>';
    
    /* 
    // When meal planning is implemented, uncomment and modify this code
    $meals = $db->fetchAll("SELECT * FROM planned_meals ORDER BY date ASC LIMIT 5");
    
    if (count($meals) > 0) {
        echo '<div class="list-group">';
        foreach ($meals as $meal) {
            echo '<a href="/meal-details?id=' . $meal['id'] . '" class="list-group-item list-group-item-action">';
            echo '<div class="d-flex w-100 justify-content-between">';
            echo '<h5 class="mb-1">' . htmlspecialchars($meal['meal_name']) . '</h5>';
            echo '<small>' . htmlspecialchars($meal['date']) . '</small>';
            echo '</div>';
            echo '</a>';
        }
        echo '</div>';
    } else {
        echo '<div class="text-center p-3">';
        echo '<p class="mb-0">No upcoming meals planned yet.</p>';
        echo '</div>';
    }
    */
} catch (\Exception $e) {
    echo '<div class="alert alert-danger">Error loading upcoming meals: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>

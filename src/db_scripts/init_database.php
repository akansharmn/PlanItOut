<?php
namespace PlanItOut;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../debug.php';

use PlanItOut\Database;
use PDOException;

try {
    // Get the database connection
    $db = Database::getInstance();
    
    echo "<h2>Initializing Database Tables</h2>";
    
    // Create Recipes table
    echo "<p>Creating Recipes table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS recipes (
            id SERIAL PRIMARY KEY,
            recipe_name VARCHAR(255) NOT NULL,
            ingredients TEXT,
            pre_preparations TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Create MealTypes table (based on your enum)
    echo "<p>Creating MealTypes table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS meal_types (
            id SERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE
        )
    ");
    
    // Insert meal types from the enum
    $mealTypes = ['BREAKFAST', 'LUNCH', 'DINNER'];
    foreach ($mealTypes as $type) {
        $db->query("
            INSERT INTO meal_types (name) 
            VALUES (?) 
            ON CONFLICT (name) DO NOTHING
        ", [$type]);
    }
    
    // Create Weekdays table (based on your enum)
    echo "<p>Creating Weekdays table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS weekdays (
            id SERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE
        )
    ");
    
    // Insert weekdays from the enum
    $weekdays = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'];
    foreach ($weekdays as $day) {
        $db->query("
            INSERT INTO weekdays (name) 
            VALUES (?) 
            ON CONFLICT (name) DO NOTHING
        ", [$day]);
    }
    
    // Create WeeklyPlans table
    echo "<p>Creating WeeklyPlans table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS weekly_plans (
            id SERIAL PRIMARY KEY,
            year INT NOT NULL,
            week INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(year, week)
        )
    ");
    
    // Create DayMealPlans table
    echo "<p>Creating WeekDayMealPlans table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS week_day_meal_plans (
            id SERIAL PRIMARY KEY,
            weekly_plan_id INT NOT NULL,
            weekday_id INT NOT NULL,
            meal_type_id INT NOT NULL,
            recipe_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (weekly_plan_id) REFERENCES weekly_plans(id) ON DELETE CASCADE,
            FOREIGN KEY (weekday_id) REFERENCES weekdays(id),
            FOREIGN KEY (meal_type_id) REFERENCES meal_types(id),
            FOREIGN KEY (recipe_id) REFERENCES recipes(id),
            UNIQUE(weekly_plan_id, weekday_id, meal_type_id, recipe_id)
        )
    ");
    
    // Create MealPreferences table
    echo "<p>Creating MealPreferences table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS meal_preferences (
            id SERIAL PRIMARY KEY,
            meal_type_id INT NOT NULL,
            recipe_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (meal_type_id) REFERENCES meal_types(id),
            FOREIGN KEY (recipe_id) REFERENCES recipes(id),
            UNIQUE(meal_type_id, recipe_id)
        )
    ");


    // Create users table if it doesn't exist
    echo "<p>Creating Users table...</p>";
    $db->query("
        CREATE TABLE IF NOT EXISTS users (
            username TEXT PRIMARY KEY,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    Logger::debug('Database tables created successfully');
    echo "<p>Database tables initialized successfully.</p>";

    
    echo "<p>Database initialization completed successfully!</p>";
    
    // Display table structure
    $tables = $db->fetchAll("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "<h3>Created Tables:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>{$table['table_name']}</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p>Database error: " . $e->getMessage() . "</p>";
}
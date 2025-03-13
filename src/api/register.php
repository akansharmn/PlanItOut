
<?php
namespace PlanItOut\Api;

require_once 'src/Database.php';
require_once 'src/auth/AuthManager.php';
use PlanItOut\Database;
use PlanItOut\Auth\AuthManager;

// Check if it's an HTMX request
$isHtmxRequest = isset($_SERVER['HTTP_HX_REQUEST']);

// If not an HTMX request, include the header
if (!$isHtmxRequest) {
    include 'src/templates/header.php';
}

// Process registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    $errors = [];
    
    // Validate input
    if (empty($username)) {
        $errors[] = 'Username is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match';
    }
    
    // If there are no validation errors, proceed with registration
    if (empty($errors)) {
        try {
            // Get the Auth manager instance
            $auth = AuthManager::getInstance();
            
            // Register the user
            if ($auth->register($username, $email, $password)) {
                // Successful registration
                if ($isHtmxRequest) {
                    // Return HTML response for HTMX
                    echo '<div id="form-response" class="alert alert-success mt-3" role="alert">';
                    echo '<i class="bi bi-check-circle-fill me-2"></i>';
                    echo 'Registration successful! <a href="/login">Login now</a>';
                    echo '</div>';
                    
                    // Add a trigger to reset the form
                    header("HX-Trigger: {\"resetForm\": true}");
                } else {
                    // Redirect to login page
                    header('Location: /login');
                    exit;
                }
            } else {
                // Registration failed
                $errors[] = 'Username or email already exists';
            }
        } catch (\Exception $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
    
    // If there are errors, display them
    if (!empty($errors)) {
        if ($isHtmxRequest) {
            // Return HTML error response for HTMX
            echo '<div id="form-response" class="alert alert-danger mt-3" role="alert">';
            echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
            echo '<ul class="mb-0">';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        } else {
            // Return JSON for API clients
            echo json_encode([
                'status' => 'error',
                'errors' => $errors
            ]);
        }
    }
} else {
    // Include the registration form template
    include 'src/api/register.htmx';
}

// If not an HTMX request, include the footer
if (!$isHtmxRequest) {
    include 'src/templates/footer.php';
}

<?php
namespace PlanItOut\Auth;

require_once 'src/Database.php';
require_once 'src/Logger.php';
use PlanItOut\Database;
use PlanItOut\Logger;

class AuthManager {
    private static $instance = null;
    private $db;

    // Private constructor - only getInstance() can create an instance
    private function __construct() {
        $this->db = Database::getInstance();
    }

    // Singleton pattern to get the instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a new user
     * 
     * @param string $username The username
     * @param string $email The email address
     * @param string $password The password (will be hashed)
     * @return bool True if registration successful, false otherwise
     */
    public function register($username, $email, $password) {
        try {
            // Check if username already exists
            $existingUser = $this->db->fetch(
                "SELECT * FROM users WHERE username = ? OR email = ?",
                [$username, $email]
            );

            if ($existingUser) {
                // Username or email already exists
                return false;
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user
            $this->db->insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ]);

            return true;
        } catch (\Exception $e) {
            Logger::error('Registration error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Authenticate a user
     * 
     * @param string $username The username
     * @param string $password The password
     * @return bool True if authentication successful, false otherwise
     */
    public function login($username, $password) {
        try {
            // Get the user by username
            $user = $this->db->fetch(
                "SELECT * FROM users WHERE username = ?",
                [$username]
            );

            if (!$user) {
                // User not found
                return false;
            }

            // Verify the password
            if (!password_verify($password, $user['password'])) {
                // Invalid password
                return false;
            }

            // Start a session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Store user info in session
            $_SESSION['user'] = [
                'username' => $user['username'],
                'email' => $user['email']
            ];

            return true;
        } catch (\Exception $e) {
            Logger::error('Login error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a user is logged in
     * 
     * @return bool True if logged in, false otherwise
     */
    public function isLoggedIn() {
        // Start a session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user']);
    }

    /**
     * Get the current logged in user
     * 
     * @return array|null User data or null if not logged in
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $_SESSION['user'];
    }

    /**
     * Log out the current user
     */
    public function logout() {
        // Start a session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Unset user session data
        unset($_SESSION['user']);

        // Destroy the session
        session_destroy();
    }
}
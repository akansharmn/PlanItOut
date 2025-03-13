<?php
namespace PlanItOut\Auth;

class AuthManager {
    private static $instance = null;
    
    private function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function getInstance(): AuthManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function login(string $username, string $password): bool {
        // Simple authentication check - in a real app, use password_hash/password_verify
        // This is just a placeholder - replace with proper DB validation
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user'] = [
                'id' => 1,
                'username' => $username,
                'role' => 'admin'
            ];
            return true;
        }
        return false;
    }
    
    public function logout(): void {
        // Unset user session
        unset($_SESSION['user']);
        
        // Destroy the session
        session_destroy();
    }
    
    public function isLoggedIn(): bool {
        return isset($_SESSION['user']);
    }
    
    public function getCurrentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }
    
    public function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            // Redirect to login page if not logged in
            header('Location: /login');
            exit;
        }
    }
}
<?php
namespace PlanItOut\Auth;

require_once 'src/Database.php';
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
            $existingUser = $this->db->fetchOne(
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
            $user = $this->db->fetchOne(
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
     * @return bool True if user is logged in, false otherwise
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
        // Start a session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Log out the current user
     */
    public function logout() {
        // Start a session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset user data
        unset($_SESSION['user']);
        
        // Destroy the session
        session_destroy();
    }
}

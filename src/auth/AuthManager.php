
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

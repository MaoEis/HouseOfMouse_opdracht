<?php
require_once __DIR__ . '/Db.php';
require_once __DIR__ . '/Users.php';

class LoginHandler {
    private $db;

    public function __construct() {
        $this->db = Db::getConnection(); // Get database connection
    }

    public function handleLogin($email, $password) {
        // Sanitize inputs
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

        if (empty($email) || empty($password)) {
            return "Email and password cannot be empty.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Please enter a valid email address.";
        }

        try {
            $user = new Users();
            $isAdmin = $user->canLogin($email, $password);

             error_log("canLogin result: " . print_r($isAdmin, true));

            // Debug output
            var_dump($isAdmin);

            if ($isAdmin !== false) {
                // Secure session management
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['user_email'] = $email;
                $_SESSION['isAdmin'] = $isAdmin['isAdmin'];
                $_SESSION['user_id'] = $isAdmin['user_id'];

                // Redirect based on user type
                return $isAdmin['isAdmin'] === 'admin' ? 'adminIndex.php' : 'index.php';
            } else {
                return "Invalid email or password.";
            }
        } catch (Throwable $th) {
            error_log($th->getMessage()); // Log error for debugging
            return "Something went wrong. Please try again later.";
        }
    }
}

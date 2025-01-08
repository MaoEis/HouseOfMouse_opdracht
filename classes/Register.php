<?php
require_once __DIR__ . '/Db.php';
require_once __DIR__ . '/Users.php';

class RegisterHandler {
    private $db;

    public function __construct() {
        $this->db = Db::getConnection(); // Get database connection
    }

    public function handleRegistration($email, $password) {
        // Sanitize inputs
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

        // Validate inputs
        if (empty($email) || empty($password)) {
            return "Email and password cannot be empty.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Please provide a valid email address.";
        }

        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }

        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Save the user
            $user = new Users();
            $user->setEmail($email);
            $user->setPassword($password);
            $user->save();

            return true; // Registration successful
        } catch (Throwable $th) {
            error_log($th->getMessage()); // Log error for debugging
            return "An error occurred during registration. Please try again.";
        }
    }
}

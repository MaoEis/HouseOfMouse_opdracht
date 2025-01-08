<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Users {
    protected $email;
    protected $password;
    protected $currency;

    const PASSWORD_COST = 13;

    // Email
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        $this->email = $email;
        return $this;
    }

    // Password
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        if (empty($password)) {
            throw new Exception('Password cannot be empty.');
        }
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long.');
        }

        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => self::PASSWORD_COST]);
        return $this;
    }

    // Currency
    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency) {
        $this->currency = htmlspecialchars($currency, ENT_QUOTES, 'UTF-8');
        return $this;
    }

    // Check if user exists
    private function userExists() {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT id FROM users WHERE email = :email');
        $statement->bindValue(':email', $this->email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Save user
    public function save() {
        if ($this->userExists()) {
            throw new Exception('Email is already in use.');
        }

        $conn = Db::getConnection();
        $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        $statement->execute();
    }


public function canLogin($email, $password) {
    $conn = Db::getConnection();
    $statement = $conn->prepare(
        "SELECT id, password, isAdmin FROM users WHERE email = :email"
    );
    $statement->bindValue(':email', $email);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        return [
            'user_id' => $user['id'],
            'isAdmin' => $user['isAdmin'],
        ];
    }
    return false;
}


   // getCurrentUser method
public static function getCurrentUser($email) {
    $conn = Db::getConnection();
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $statement->bindValue(':email', $email);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

// getCurrencyAmount method
public static function getCurrencyAmount($email) {
    $conn = Db::getConnection();
    $statement = $conn->prepare("SELECT currency FROM users WHERE email = :email");
    $statement->bindValue(':email', $email);
    $statement->execute();

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result ? htmlspecialchars($result['currency'], ENT_QUOTES, 'UTF-8') : null;
}


    // Change password
    public function changePassword($oldPassword, $newPassword) {
        if (!$this->email) {
            throw new Exception('Email address is not set.');
        }

        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT password FROM users WHERE email = :email');
        $statement->bindValue(':email', $this->email);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$user || !password_verify($oldPassword, $user['password'])) {
            throw new Exception('Invalid current password.');
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => self::PASSWORD_COST]);
        $updateStatement = $conn->prepare('UPDATE users SET password = :password WHERE email = :email');
        $updateStatement->bindValue(':password', $newPasswordHash);
        $updateStatement->bindValue(':email', $this->email);
        $updateStatement->execute();

        return true;
    }
}

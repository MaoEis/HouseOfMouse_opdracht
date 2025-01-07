<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Users{
    protected $email;
    protected $password;
    protected $currency;

    //email
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
     if(empty($email)) 
        {                
        throw new Exception('Email cannot be empty');
        }
        
        $this->email = $email;
        return $this;
    }


    //password
    public function getPassword()
    {
        return $this->password;
    }


    public function setPassword($password)
    {
            if (empty($password)) 
            {
                throw new Exception('Password cannot be empty');
            }
        
            $options = 
            [
                'cost' => 13,
            ];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);
            $this->password = $hashedPassword;
            return $this;
    }

        public function getCurrency()
    {
        return $this->currency;
    }


    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    //login
  function canLogin($email, $password) {
    try {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            session_start(); // Ensure session is started
            $_SESSION['user_email'] = $user['email']; // Store user's email in session
            $_SESSION['user_id'] = $user['id'];      // Optional: Store user's ID
            return [
                'user_id' => $user['id'],
                'isAdmin' => $user['isAdmin']
            ];
        } else {
            error_log("Login failed for user: $email");
            return false;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        return false;
    }
}

     public function save(){
        $conn = Db::getConnection();

        if ($this->userExists()) {
            throw new Exception('Email already in use');
        } 
        $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        $statement->execute();

        header('Location: login.php');
        exit();
        }

        // af private function for userExists()
        private function userExists(){
            $conn = Db::getConnection();
            $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
            $statement->bindValue(':email', $this->email);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
  

        //function to get userid
        public function getUserId($user_id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT id FROM users WHERE id = :user_id');
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user; // Returns a single user ID or false if not found
}

private function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

public static function getCurrencyAmount($email) {
    try {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT currency FROM users WHERE email = :email");
        $statement->bindValue(':email', $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return htmlspecialchars($result['currency'], ENT_QUOTES, 'UTF-8');
        } else {
            throw new Exception("User not found");
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return null;
    }
}

public static function getCurrentUser($email) {
    $conn = Db::getConnection();
    $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $statement->bindValue(':email', $email);
    $statement->execute();
    
    // Return user data or false if not found
    return $statement->fetch(PDO::FETCH_ASSOC);
}


    //function for changing password
    public function changePassword($oldPassword, $newPassword) {
          if (!$this->email) {
            throw new Exception('Email address is not set');
        }

        $conn = Db::getConnection();

        try {
            $conn->beginTransaction();
            $statement = $conn->prepare('SELECT PASSWORD FROM users WHERE email = :email');
            $statement->bindParam(':email', $this->email);
            $statement->execute();
            $user = $statement->fetch();
    
            if ($user && isset($user['PASSWORD']) && !is_null($user['PASSWORD'])) {
                if (password_verify($oldPassword, $user['PASSWORD'])) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => 12]);
    
                    $updateStatement = $conn->prepare('UPDATE users SET PASSWORD = :password WHERE email = :email');
                    $updateStatement->bindParam(':password', $newPasswordHash);
                    $updateStatement->bindParam(':email', $this->email);
    
                    // Gebruik een transactie
                    $updateStatement->execute();
                    $conn->commit();
    
                    return true;
                } else {
                    throw new Exception('Onjuist huidig passwoord');
                    $result['div'] = 'current_password';
                }
            } else {
                throw new Exception('User not found');
            }
        } catch (Exception $e) {
            $conn->rollBack(); 
            throw $e;
        }
       
    } 
}
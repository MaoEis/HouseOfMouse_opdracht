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
            return [
                'user_id' => $user['id'],    // Return the user ID
                'isAdmin' => $user['isAdmin'] // Return the isAdmin value
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
}
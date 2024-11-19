<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Users{
    protected $email;
    protected $password;

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

    //login
    function canLogin($email, $password){
		$conn = Db::getConnection();
		$statement = $conn->prepare("select * from users where email = :email");
		$statement->bindValue(":email", $email);
		$statement->execute();

		$user = $statement->fetch(PDO::FETCH_ASSOC);
			if($user){
			$hash = $user['password'];
			if(password_verify($password, $hash)){
				return true;
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}

     public function save(){
        $conn = Db::getConnection();
        $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        return $statement->execute();
        }

    //   public static function getAll(){
    //     $conn = Db::getConnection();
    //     $statement = $conn->query('SELECT * FROM users');
    //     $statement->execute();
    //     return $statement->fetchAll(PDO::FETCH_ASSOC);
    //     }

}
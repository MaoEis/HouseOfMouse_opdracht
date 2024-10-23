<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");
class Users {
        private $email;
        private $password; 

        // ------------------- getter & setter start hier
   
        public function getEmail(){
                return $this->email;
        }

        public function setEmail($email){
            if(empty($email)) {
                throw new Exception('Email cannot be empty');
            }
            $this->email = $email;
            return $this;
        }
      
        public function getPassword(){
                return $this->password;
        }

        //Set the value of lastname @return  self
       public function setPassword($password){
        if (empty($password)) {
            throw new Exception('Password cannot be empty');
        }
        
        $options = [
            'cost' => 13,
        ];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);
        $this->password = $hashedPassword;
        return $this;
    }

        public function save(){
            $conn = Db::getConnection();
            $statement = $conn->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            return $statement->execute();
        }

        public static function getAll(){
            $conn = Db::getConnection();
            $statement = $conn->query('SELECT * FROM users');
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
}

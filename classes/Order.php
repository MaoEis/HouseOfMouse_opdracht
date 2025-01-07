<?php

class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createOrder($userId, $firstname, $lastname, $email, $address, $city, $zip, $totalPrice) {
        $sql = "INSERT INTO `orders` (`user_id`, `firstname`, `lastname`, `email`, `address`, `city`, `zip`, `total_price`) 
                VALUES (:user_id, :firstname, :lastname, :email, :address, :city, :zip, :total_price)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':city', $city, PDO::PARAM_STR);
        $stmt->bindValue(':zip', $zip, PDO::PARAM_STR);
        $stmt->bindValue(':total_price', $totalPrice, PDO::PARAM_STR);

        return $stmt->execute();
    }
}

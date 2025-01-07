<?php 
include_once(__DIR__ . "/Db.php");



class Cart {
    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            $conn = Db::getConnection();

            // Check if the product already exists in the cart for the user
            $checkQuery = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $checkQuery->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $checkQuery->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $checkQuery->execute();

            if ($checkQuery->rowCount() > 0) {
                // If the product already exists, update the quantity
                $updateQuery = $conn->prepare("
                    UPDATE cart 
                    SET quantity = quantity + :quantity 
                    WHERE user_id = :user_id AND product_id = :product_id
                ");
                $updateQuery->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $updateQuery->bindValue(':product_id', $productId, PDO::PARAM_INT);
                $updateQuery->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                $updateQuery->execute();
            } else {
                // If the product doesn't exist, insert a new row
                $insertQuery = $conn->prepare("
                    INSERT INTO cart (user_id, product_id, quantity) 
                    VALUES (:user_id, :product_id, :quantity)
                ");
                $insertQuery->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $insertQuery->bindValue(':product_id', $productId, PDO::PARAM_INT);
                $insertQuery->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                $insertQuery->execute();
            }

            return true;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}


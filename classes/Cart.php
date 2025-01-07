<?php 
include_once(__DIR__ . "/Db.php");

class Cart {
    private $id;
    private $user_id;
    private $product_id;
    private $quantity;
    private $conn;

    public function __construct($id = null, $user_id = null, $product_id = null, $quantity = null) {
    $this->conn = Db::getConnection();  // Establish connection once
    $this->id = $id;
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->quantity = $quantity;
}


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getUser_id() {
        return $this->user_id;
    }

    public function setUser_id($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function getProduct_id() {
        return $this->product_id;
    }

    public function setProduct_id($product_id) {
        $this->product_id = $product_id;
        return $this;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            // Check if product exists
            $checkQuery = $this->conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $checkQuery->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $checkQuery->bindValue(':product_id', $productId, PDO::PARAM_INT);
            $checkQuery->execute();

            if ($checkQuery->rowCount() > 0) {
                // Update quantity
                $updateQuery = $this->conn->prepare("
                    UPDATE cart 
                    SET quantity = quantity + :quantity 
                    WHERE user_id = :user_id AND product_id = :product_id
                ");
                $updateQuery->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $updateQuery->bindValue(':product_id', $productId, PDO::PARAM_INT);
                $updateQuery->bindValue(':quantity', $quantity, PDO::PARAM_INT);
                $updateQuery->execute();
            } else {
                // Insert new item
                $insertQuery = $this->conn->prepare("
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

   public function getCartItems($userId) {
    try {
        $query = $this->conn->prepare("
            SELECT c.product_id, c.quantity, p.title, p.description, p.price, u.fileName
            FROM cart c
            INNER JOIN products p ON c.product_id = p.id
            INNER JOIN uploads u ON p.upload_id = u.id  -- Adjust this join condition
            WHERE c.user_id = :user_id
        ");
        $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}


    public function getTotalPrice($userId) {
        try {
            $query = $this->conn->prepare("
                SELECT SUM(p.price * c.quantity) AS total
                FROM cart c
                INNER JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id
            ");
            $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return 0;
        }
    }
}

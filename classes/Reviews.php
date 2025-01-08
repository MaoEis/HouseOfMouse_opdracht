<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Reviews{
    protected $user_id;
    protected $product_id;
    protected $rating;
    protected $comment;

    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
        return $this;
    }

    public function setRating($rating) {
        $this->rating = $rating;
        return $this;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }

    // Save review to the database
    public function save() {
        try {
            $conn = Db::getConnection();
            $sql = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->product_id);
            $stmt->bindParam(3, $this->rating);
            $stmt->bindParam(4, $this->comment);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Failed to save review.");
            }
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

     public function getReviewsByProductId($productId) {
        try {
            $sql = "SELECT * FROM reviews WHERE product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch reviews: " . $e->getMessage());
        }
    }
    public function getReviews($productId) {
        try {
            $conn = Db::getConnection();
            $sql = "SELECT * FROM reviews WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch reviews: " . $e->getMessage());
        }
    }
}
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Products {
    protected $title;
    protected $description;
    protected $amount;
    protected $category_id;
    protected $price;
    protected $height;
    protected $diameter;
    protected $width;
    protected $upload_id; 
    protected $id;
    private $db;

    public function __construct() {
        $this->db = Db::getConnection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
   

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        if(empty($title)) 
        {                
        throw new Exception('Title cannot be empty');
        }
        
        $this->title = $title;
        return $this;
    }

    //description
    public function getDescription()
    {
        return $this->description;
    }
    

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

  
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

 
   public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }



    public function getPrice()
    {
        return $this->price;
    }


    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

  
    public function getHeight()
    {
        return $this->height;
    }

  
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

 
    public function getDiameter()
    {
        return $this->diameter;
    }


    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
    }

       
    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function getUpload_id()
    {
        return $this->upload_id;
    }

    public function setUploadId($upload_id) {
        $this->upload_id = $upload_id;
        return $this;
    }

     public function save() {
    try {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO products (title, description, stockAmount, category_id, price, height, diameter, width, upload_id) 
            VALUES (:title, :description, :amount, :category_id, :price, :height, :diameter, :width, :upload_id)
        ");
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':height', $this->height);
        $stmt->bindParam(':diameter', $this->diameter);
        $stmt->bindParam(':width', $this->width);
        $stmt->bindParam(':upload_id', $this->upload_id);

        if ($stmt->execute()) {
            $this->id = $conn->lastInsertId(); // Set the ID of the product
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        throw new Exception("Failed to save product: " . $e->getMessage());
    }
}
    public function getProducts($category = '', $search = '') {
    try {
        $conn = Db::getConnection();
        $sql = "
            SELECT products.*, uploads.fileName 
            FROM products 
            LEFT JOIN uploads ON products.upload_id = uploads.id
            WHERE 1=1
        ";

        // Apply category filter
        if (!empty($category)) {
            $sql .= " AND products.category_id = :category";
        }

        // Apply search filter
        if (!empty($search)) {
            $sql .= " AND (products.title LIKE :search OR products.description LIKE :search)";
        }

        $stmt = $conn->prepare($sql);

        if (!empty($category)) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw new Exception("Error fetching products: " . $e->getMessage());
    }
}

    //public function getProductsbyId()
    public function getProductById($product_id) {
    try {
        $conn = Db::getConnection();
        $sql = "
            SELECT * 
            FROM products 
            WHERE id = :product_id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT); // Fix here
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        throw new Exception("Error fetching product: " . $e->getMessage());
    }
}

public function getProductWithFileName($product_id)
{
    try {
        $conn = Db::getConnection();
        $sql = "
            SELECT p.*, u.fileName
            FROM products p
            LEFT JOIN uploads u ON p.upload_id = u.id  -- Join on upload_id from products to id in uploads
            WHERE p.id = :product_id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching product with file name: " . $e->getMessage());
    }
}

public function getCategories() {
    try {
        $conn = Db::getConnection();
        $sql = "SELECT id, name FROM category"; // Assuming your categories table has 'id' and 'name'
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching categories: " . $e->getMessage());
    }
}


public function getColors() {
    try {
        $conn = Db::getConnection();
        $sql = "SELECT id, name FROM colors"; // Assuming your colors table has 'id' and 'name'
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching colors: " . $e->getMessage());
    }
}

public function getMaterials() {
    try {
        $conn = Db::getConnection();
        $sql = "SELECT id, name FROM materials"; // Assuming your materials table has 'id' and 'name'
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Error fetching materials: " . $e->getMessage());
    }
}
public function update() {
    try {
        $conn = Db::getConnection();
        $stmt = $conn->prepare("
            UPDATE products 
            SET title = :title, description = :description, stockAmount = :amount, category_id = :category_id, price = :price, height = :height, diameter = :diameter, width = :width 
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':height', $this->height);
        $stmt->bindParam(':diameter', $this->diameter);
        $stmt->bindParam(':width', $this->width);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage();
        return false;
    }
}

public function deleteProduct($productId) {
        try {
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $productId, PDO::PARAM_INT);
            return $stmt->execute(); // Returns true on success
        } catch (PDOException $e) {
            // Log error or handle it as needed
            return false;
        }
    }

    public function searchProducts($searchTerm) {
        $searchParam = '%' . $searchTerm . '%';
        $sql = "SELECT * FROM products WHERE title LIKE :search";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
}
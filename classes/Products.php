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

    //title
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
        // Database connection
        $conn = Db::getConnection();

        // Insert product information into the database
        $sql = "INSERT INTO products (title, description, stockAmount, category_id, price, height, diameter, width, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $this->title);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->amount);
        $stmt->bindParam(4, $this->category_id);
        $stmt->bindParam(5, $this->price);
        $stmt->bindParam(6, $this->height);
        $stmt->bindParam(7, $this->diameter);
        $stmt->bindParam(8, $this->width);
        $stmt->bindParam(9, $this->upload_id);

        if ($stmt->execute()) {
             echo "Product added successfully.<br>";
            return true;
        } else {
       echo "Failed to execute SQL statement.<br>";
            throw new Exception("Error: " . $stmt->errorInfo()[2]);
        }
    } catch (PDOException $e) {
         echo "Database error: " . $e->getMessage() . "<br>";
        throw new Exception("Database error: " . $e->getMessage());
    }
}
   public function getId() {
        return $this->id; // Return the product ID
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

  
}
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/Db.php");

class Products {
    protected $title;
    protected $description;
    protected $amount;
    protected $category;
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

 
    public function getCategory()
    {
        return $this->category;
    }

   
    public function setCategory($category)
    {
        $this->category = $category;

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
         echo "Database connection established.<br>";

        // Insert product information into the database
        $sql = "INSERT INTO products (title, description, stockAmount, categoryId, price, height, diameter, width, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $this->title);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->amount);
        $stmt->bindParam(4, $this->category);
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

  
}
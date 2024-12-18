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
    protected $picture;

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

 
    public function getPicture()
    {
        return $this->picture;
    }

 
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }
}
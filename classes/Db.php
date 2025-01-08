<?php
error_reporting(E_ALL);

class Db {
    private $host = 'junction.proxy.rlwy.net';
    private $user = 'root';
    private $pass = 'bqemkIoAJAMucwKIgVOPPkanONSUNOoZ';
    private $dbname = 'railway';
    private $port = '40235';

    public $conn;

    public function connect() {
        $this->conn= new mysqli($this->host, $this->user, $this->pass, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
    // public static function getConnection() {
    //     if (self::$conn == null) {
    //         try {
    //             self::$conn = new PDO('mysql:host=localhost;dbname=houseofmoose', 'root', '');
    //             self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //         } catch (PDOException $e) {
    //             die("Database connection failed: " . $e->getMessage());
    //         }
    //     }
    //     return self::$conn;
    // }
}

<?php
error_reporting(E_ALL);

class Db {
    private static $conn;
    private static $host = 'junction.proxy.rlwy.net';
    private static $user = 'root';
    private static $pass = 'bqemkIoAJAMucwKIgVOPPkanONSUNOoZ';
    private static $dbname = 'railway';
    private static $port = '40235';

    public static function getConnection() {
        self::$conn = new mysqli(self::$host, self::$user, self::$pass, self::$dbname, self::$port);
    if (self::$conn->connect_error) {
        die("Connection failed: " . self::$conn->connect_error);
    }
    return self::$conn;
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

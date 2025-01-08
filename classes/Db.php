<?php
error_reporting(E_ALL);

class Db {
    private static $conn;
    public static function getConnection() {
        if (self::$conn == null) {
            try {
                self::$conn = new PDO('mysql:host=localhost;dbname=houseofmoose', 'root', '');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}

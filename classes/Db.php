<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Db {
    private static $conn = null;
    public static function getConnection() {
            if (self::$conn == null) {
                echo "💩";
                self::$conn = 
                new PDO
                ('mysql:host=localhost;dbname=houseofmoose', 'root', '');
                return self::$conn;
            }
            echo "👑";
            return self::$conn;
        }
    }
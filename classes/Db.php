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


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// class Db {
//     private static $conn;
//     public static function getConnection() {
//             if (self::$conn == null) {
//                 echo "💩";
//                 self::$conn = 
//                 new PDO
//                 ('mysql:host=localhost;dbname=houseofmoose', 'root', '');
//                 // ('mysql:host=mysql.railway.internal;dbname=railway', 'root', 'bqemkIoAJAMucwKIgVOPPkanONSUNOoZ');
//                 self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//                 return self::$conn;
//             }
//             echo "👑";
//             return self::$conn;
//         }
//     }
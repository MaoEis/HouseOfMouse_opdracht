<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

include_once(__DIR__ . "/../classes/Db.php");
include_once(__DIR__ . "/../classes/Cart.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; 
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = 1; 

    if (!$productId) {
        echo json_encode(['message' => 'Invalid product ID']);
        exit;
    }

    $cart = new Cart();
    $success = $cart->addToCart($userId, $productId, $quantity);

    if ($success) {
        echo json_encode(['message' => 'Success']);
    } else {
        echo json_encode(['message' => 'Failed to add product to cart']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method']);
    exit;
}
?>

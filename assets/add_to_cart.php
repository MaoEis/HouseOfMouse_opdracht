<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'User not logged in']);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$productName = isset($_POST['product_name']) ? $_POST['product_name'] : null;

echo "Product ID: " . $productId . ", Product Name: " . $productName;

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['message' => 'Please log in first.']);
    exit;
}

// Assuming you set the user_id in session after user logs in
if (isset($_POST['product_id']) && isset($_SESSION['user_id'])) {
    $productId = (int)$_POST['product_id'];
    $userId = (int)$_SESSION['user_id'];

    // Validate that the product_id and user_id are positive integers
    if ($productId <= 0 || $userId <= 0) {
        echo json_encode(['message' => 'Invalid product or user data']);
        exit;
    }

    // Continue with the process if the validation passes
    include_once('classes/Db.php');
    include_once('classes/Cart.php');

    $productName = isset($_POST['product_name']) ? $_POST['product_name'] : null;

    if ($productId && $productName) {
        $cart = new Cart();
        if ($cart->addToCart($userId, $productId)) {
            echo json_encode(['message' => 'Product added to your cart successfully.']);
        } else {
            echo json_encode(['message' => 'Failed to add product to cart.']);
        }
    } else {
        echo json_encode(['message' => 'Invalid product data.']);
    }
} else {
    echo json_encode(['message' => 'Invalid product data or user session']);
}
?>

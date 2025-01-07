<?php
session_start();

require_once __DIR__ . '/../classes/Order.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("You must be logged in to complete your purchase.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $zip = htmlspecialchars($_POST['zip']);
    $totalPrice = $_SESSION['total_price']; // You can pass the total price from the cart page

    $order = new Order();
    $orderCreated = $order->createOrder($userId, $firstname, $lastname, $email, $address, $city, $zip, $totalPrice);

    if ($orderCreated) {
        echo "Order successfully placed!";
        // Redirect to a confirmation page or clear the cart
    } else {
        echo "There was an error placing your order.";
    }
}

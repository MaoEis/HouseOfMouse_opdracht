<?php
session_start();


ini_set('display_errors', 1);  // Display errors in the browser
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  // Report all types of errors

require_once __DIR__ . '/classes/Cart.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    die("You must be logged in to view your cart.");
}

$cart = new Cart();
$cartItems = $cart->getCartItems($userId);
$cartItems = $cart->getCartItems($userId);


$totalPrice = $cart->getTotalPrice($userId);


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">

    <title>My Bag</title>
</head>
<body>
    <?php include_once("nav.inc.php"); ?>
    <div class="checkOut">
        <div class="allItemstoBuy">
            <h3 class="bagTitle">My Bag</h3>
            <?php if (!empty($cartItems)): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="itemToBuy">
                    <div class="bagImgAndInfo">
                    <img src="uploads/<?php echo htmlspecialchars($item['fileName']); ?>" alt="" class="itemPic">
                        <div class="bagItemInfo">
                            <h4 class="itemTitle"><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p class="itemDescr"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="itemAmount">x<?php echo (int) $item['quantity']; ?></p>
                        </div>
                    </div>
                    <div class="bagItemPrice">
                        <p class="itemPrice"><?php echo number_format($item['price'], 2); ?> Euro</p>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="totalPrice">
                    <p class="total">Total:</p>
                    <p class="totalAmount"><?php echo number_format($totalPrice, 2); ?> Euro</p>
                </div>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>

        </div>
    </div>

    <div class="buyerInfo">
        <h3 class="buyerTitle">Buyer Information</h3>
        <form class="checkOutInfo" action="checkout.php" method="post">
            <label for="firstname">Firstname:</label>
            <input type="text" id="firstname" name="firstname" required>
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="address">Address and number:</label>
            <input type="text" id="address" name="address" required>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
            <label for="zip">Zip:</label>
            <input type="text" id="zip" name="zip" required>
            <p class="yourCredit">Your Credit: </p>
            <input type="submit" value="Buy now">
        </form>
    </div>
</body>
</html>
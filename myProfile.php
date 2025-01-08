<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if (!isset($_SESSION['user_email'])) {
    header('Location: login.php'); 
    exit();
}

include_once(__DIR__ ."/classes/Users.php");
$userEmail = $_SESSION['user_email'];
$user = Users::getCurrentUser($userEmail); 


if (!$user) {
    die("User not found.");
}


$userEmail = $_SESSION['user_email'];
$userCurrency = Users::getCurrencyAmount($userEmail);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <title>My Profile</title>
</head>
<body>
    <?php include_once("nav.inc.php"); ?>
    <div class="profile">
        <h3 class="profileTitle">My Profile</h3>
        <div class="myCurrencyAmount">
            <h4 class="currencyTitle">My currency amount: € <?php echo $userCurrency ?: 'N/A'; ?></h4>
        </div>

        <div class="myprofile">
            <div class="profileInfo">
                <h3 class="profTitle">Personal Information</h3>
                <div class="profEmailInfo">
                    <h4 class="profEmail">my email:</h4>
                    <p class="profEmail"><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="profPassw">
                    <h4 class="profPassw">Change password:</h4>
                    <form id="passwordform">
                        <label for="current_password">Current password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                        <label for="new_password">New password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <label for="confirm_password">Confirm new password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>

                        <div class="error hidden" id="error"></div>

                        <button type="submit" class="changepasswordBtn">Change password</button>
                    </form>
                </div>
            </div>
            <div class="myOrders">
                <h3 class="myOrdersTitle">My orders</h3>
                <div class="order">
                    <h4 class="orderTitle">Order ID: 1</h4>
                    <p class="orderDate">Date: 2021-03-01</p>
                    <p class="orderAmount">Amount: 17 Euro</p>
                </div>
            </div>
        </div>
    </div>
    <script src="js/password.js"></script>
</body>
</html>

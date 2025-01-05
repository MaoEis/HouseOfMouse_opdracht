<!DOCTYPE html>
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
        <div class="myprofile">
        <div class="profileInfo">
            <h3 class="profTitle">Personal Information</h3>
            <div class="profEmailInfo">
                <h4 class="profEmail">Email:</h4>
                <p class="profEmail"><?php echo $user['email']; ?></p>
            </div>
            <div class="profPassw">
                <a class="changepasswordBtn" href="#">Change password</a>
            </div>


            <!-- <form class="profileForm" action="myProfile.php" method="post">
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
                <input type="submit" value="Save">
            </form> -->
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
</body>
</html>
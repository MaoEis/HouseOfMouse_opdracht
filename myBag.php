<!DOCTYPE html>
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
            <div class="itemToBuy">
                <div class="bagImgAndInfo">
                    <img src="uploads/677a6c3c505736.43384508.jpeg" alt="" class="itemPic">
                    <!-- <img src="uploads/<?php echo $product['image']; ?>" alt="img" class="itemPic"> -->
                    <div class="bagItemInfo">
                        <h4 class="itemTitle">Espresso kop</h4>
                        <p class="itemDescr" >Beetje uitleg</p>
                        <p class="itemAmount" >x1</p>
                        <!-- <h4 class="itemTitle"><?php echo $product['title']; ?></h4>
                        <p class="itemDescr" ><?php echo $product['description'];?></p>
                        <p class="itemAmount" ><?php echo $product['amount'];?></p> -->
                    </div>
                </div>
                <div class="bagItemPrice">
                    <p class="itemPrice" ><?php echo $product['price'];?></p>
                    <p class="itemPrice" >17 Euro</p>
                </div>
            </div>
            <div class="totalPrice">
                    <p class="total">Total:</p>
                    <p class="totalAmount"><?php echo $product['price'];?></p>
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
    </div>
</body>
</html>
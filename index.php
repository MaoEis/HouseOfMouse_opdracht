<?php 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php');
    exit;
}

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Users.php");
include_once(__DIR__ . "/classes/Products.php");
include_once(__DIR__ . "/classes/Upload.php");


$db = Db::getConnection();

$products = new Products();
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category'], ENT_QUOTES, 'UTF-8') : '';

// Search functionality
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';
$collection = $products->getProducts($category, $search);

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HouseOfMoose Webshop</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

  <div id="keramiek">
  <?php include_once("nav.inc.php"); ?>
  
  <div class="main">
    <div class="sloganTitle">
      <h1 class="homTitle" >House Of Moose</h1>
      <h3 class="slogan" > A reason to be hom'. </h3>
    </div>
  <div class="catBtn"> 
    <a href="?category=">All</a>
    <a href="?category=1">Mug</a>
    <a href="?category=2">Soap Dispenser</a>
    <a href="?category=4">Oil Dispenser</a>
    <a href="?category=3">Plate</a>
  </div>
    <form action="" method="get">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" value="Search">
        </form>
  </div>

<!-- <div >
  <div class="colBtnIndex"> 
    <h4 class="colTitle">Colors</h4>
      <a href="?category=">All</a>
      <a href="?category=1"><div class=beige></div></a>
      <a href="?category=2"><div class=seaShell></div></a>
      <a href="?category=4"><div class=moonlight></div></a>
      <a href="?category=3"><div class=whitewood></div></a>
      <a href="?category=3"><div class=speckled></div></a>
    </div>
  
  <div class="metBtnIndex"> 
    <h4 class="colTitle">Metal</h4>
      <a href="?category="><div class=silver></div></a>
      <a href="?category=1"><div class=black></div></a>
      <a href="?category=2"><div class=darkGold></div></a>
    </div>
</div> -->

  <div class="collection">
        <?php foreach ($collection as $c): ?>
            <div class="collectionItem">
                <a href="productPage.php?id=<?php echo $c['id']; ?>">
                    <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($c['fileName'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8'); ?>" class="collectionImage">
                </a>
                <a class="collectionTitle" href="productPage.php?id=<?php echo $c['id']; ?>">
                    <?php echo htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <button class="indexAddBtn" data-product-id="<?php echo $c['id']; ?>" data-product-name="<?php echo htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8'); ?>">
                    ADD TO BAG | € <?php echo htmlspecialchars($c['price'], ENT_QUOTES, 'UTF-8'); ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
  
</div>
<script>
$(".indexAddBtn").click(function() {
        var productId = $(this).data("product-id");
        var productName = $(this).data("product-name");

        $.ajax({
            url: 'assets/add_to_cart.php',
            method: 'POST',
            data: {
                product_id: productId,
                product_name: productName
            },
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert('Failed to add product to cart');
            }
        });
    });
  </script>
</body>
</html>
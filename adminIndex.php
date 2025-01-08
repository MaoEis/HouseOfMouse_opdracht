<?php 
session_start();

if($_SESSION['loggedin'] !== true){
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
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';
$collection = $products->getProducts($category, $search);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Use the deleteProduct method
    if ($products->deleteProduct($productId)) {
        echo 'success';
        exit();
    } else {
        echo 'error';
        exit();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HouseOfMoose Webshop</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/adminIndex.js" defer></script>
</head>
<body>

  <div id="keramiek">
  <?php include_once("navAdmin.inc.php"); ?>
  
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

  <div class="collection">
  <?php foreach($collection as $key => $c): ?>
    <div class="collectionItem">
       <a href="productPageAdded.php?id=<?php echo $c['id']; ?>">
                    <img src="uploads/<?php echo htmlspecialchars($c['fileName'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8'); ?>" class="collectionImage">
                </a>
                <a class="collectionTitle" href="productPageAdded.php?id=<?php echo $c['id']; ?>">
                    <?php echo htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
        <div class="adminIndexBtns" id="adminBtns">
            <button class="indexAddBtnAdmin adminEdit">
              <a class="indexAddBtnAdmin"  href="myAdmin.php?id=<?php echo $c['id']; ?>">EDIT</a>
            </button>
            <button class="indexAddBtnAdmin adminDel" data-product-id="<?php echo $c['id']; ?>"> DELETE </button>
        </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
<div id="confirmModal" class="modal">
  <div class="modalContent">
    <p id="confirmMessage">Are you sure you want to delete this product?</p>
    <div class="modalButtons">
      <button id="confirmYes" class="confirmBtn">Yes</button>
      <button id="confirmNo" class="confirmBtn">No</button>
    </div>
  </div>
</div>
<div id="customModal" class="modal">
  <div class="modalContent">
    <span id="closeModal" class="closeBtn">&times;</span>
    <p id="modalMessage"></p>
  </div>
</div>
</body>
</html>

<?php 
session_start();
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SESSION['loggedin'] !== true){
  header('location: login.php');
  exit;
}

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Users.php");
include_once(__DIR__ . "/classes/Products.php");
include_once(__DIR__ . "/classes/Upload.php");


$db = Db::getConnection();
$query = $db->prepare("
    SELECT products.*, uploads.fileName 
    FROM products, uploads 
    WHERE products.upload_id = uploads.id
");
$query->execute();
$collection = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Delete query
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $productId, PDO::PARAM_INT);
if ($stmt->execute()) {
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
<script>
$(document).ready(function() {
      $('.adminDel').click(function() {
          var productId = $(this).data('product-id');
          console.log("Delete button clicked, product ID:", productId);

          if (confirm('Are you sure you want to delete this product?')) {
              $.ajax({
                  url: 'adminIndex.php',
                  type: 'POST',
                  data: { id: productId, action: 'delete' },
                  success: function(response) {
    console.log("AJAX request successful, response:", response);
    console.log("Response received:", JSON.stringify(response.trim()));
    if (response.trim() === 'success') { // Use trim() here
        alert('Product deleted successfully.');
        location.reload(); // Reload the page
    } else {
        alert('Failed to delete the product.');
    }
},
                  error: function(xhr, status, error) {
    console.error("AJAX request failed:", xhr.responseText, status, error);
}
              });
          }
      });
  });

</script>
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
    <a href="#">All</a>
    <a href="#">Mug</a>
    <a href="#">Soap dispenser</a>
    <a href="#">Oil dispenser</a>
    <a href="#">Plate</a>
  </div>
   <form action="" method="get">
        <input type="text" name="search">
        <input type="submit" value="Search">
      </form>
  </div>

  <div class="collection">
  <?php foreach($collection as $key => $c): ?>
    <div class="collectionItem">
       <a href="productPage.php?id=<?php echo $c['id']; ?>">
            <img src="/HouseOfMoose_opdracht/uploads/<?php echo $c['fileName']; ?>" alt="<?php echo $c['title']; ?>" class="collectionImage">
        </a>
        <a class="collectionTitle" href="productPage.php?id=<?php echo  $c['id']; ?>"><?php echo $c['title']; ?></a>
        <div class="adminIndexBtns" id="adminBtns">
          <button class="indexAddBtnAdmin adminEdit" > EDIT </button>
          <button class="indexAddBtnAdmin adminDel" data-product-id="<?php echo $c['id']; ?>"> DELETE </button>
        </div>
    </div>
  <?php endforeach; ?>
  </div>
</div>
</body>
</html>

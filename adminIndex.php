<?php 
session_start();

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
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HouseOfMoose Webshop</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">

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
          <button class="indexAddBtnAdmin adminDel"> DELETE </button>
        </div>
    </div>
  <?php endforeach; ?>
  </div>
  
</div>

</body>
</html>

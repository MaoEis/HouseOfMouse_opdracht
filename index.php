<?php 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SESSION['loggedin'] !== true){
  header('location: login.php');
}

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Users.php");
include_once(__DIR__ . "/classes/Products.php");

$db = Db::getConnection();
$query = $db->prepare("SELECT * FROM products");
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
  <?php include_once("nav.inc.php"); ?>
  
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
    <div class="collentionItem">
        <!-- <a href="details.php?id=<?php echo $key; ?>" class="collectionImage" style="background-image: url('<?php echo $c['poster'];?>')">
        </a> -->
        <a class="collectionTitle" href="details.php?id=<?php echo $key; ?>"><?php echo $c['Title']; ?></a>
        <p>€ <?php echo $c['Price']; ?></p>
    </div>
  <?php endforeach; ?>
  </div>
  
</div>

</body>
</html>

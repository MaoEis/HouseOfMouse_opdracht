<?php 

session_start();
if($_SESSION['loggedin'] !== true){
  header('location: login.php');
}

include_once("data.inc.php");

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Keramiek Webshop</title>
  <link rel="stylesheet" href="css/index.css">
</head>
<body>

  <div id="keramiek">
  <?php include_once("nav.inc.php"); ?>
  
  <div class="collection">
  <?php foreach($collection as $key => $c): ?>
    <div class="collentionItem">
        <a href="details.php?id=<?php echo $key; ?>" class="collectionImage" style="background-image: url('<?php echo $c['poster'];?>')">
        </a>
        <a class="collectionTitle" href="details.php?id=<?php echo $key; ?>"><?php echo $c['title']; ?></a>
        <p>€ <?php echo $c['amount']; ?></p>
    </div>
  <?php endforeach; ?>
  </div>
  
</div>

</body>
</html>

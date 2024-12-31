<?php 

include_once(__DIR__ . "/classes/Db.php");

$db = Db::getConnection();
$query = $db->prepare("SELECT * FROM teamMembers");
$query->execute();
$teamMember = $query->fetchAll(PDO::FETCH_ASSOC);


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="css/index.css">
    <title>Our Team</title>


</head>
<body>
    <?php include_once("nav.inc.php"); ?>
    <h3 class="slogan" id="teamTitle" > Maak kennis met ons Team. </h3>
    <div class="theTeam">
        <?php foreach($teamMember as $key => $t): ?>
        <div class="teamMember">
            <!-- <img src="./assets/Team1.jpg" alt="Team1"> -->
            <h4><?php echo $t['name']; ?></h4>
            <p><?php echo $t['description'];?></p>
        </div>
          <?php endforeach; ?>
    </div>
</body>
</html>
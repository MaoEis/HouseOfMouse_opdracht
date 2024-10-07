<?php 
    session_start(); //session opstarten zodat de server weet wie ge bent en wat uw data is
    session_destroy(); //alle data weggooien
    header("location: login.php");
?>
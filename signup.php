<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!empty($_POST)){
       	$email= $_POST['email'];	
		$options = [
		'cost' => 13,
	];
	$hash = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);
	$conn = new PDO('mysql:host=127.0.0.1;dbname=houseofmoose', "root", "");
	$statement = $conn->prepare("insert into users (email, password) values (:email, :password)");
	$statement->bindValue(":email", $email);
	$statement->bindValue(":password", $hash);
	$statement->execute();
    header('Location: login.php');
            exit;
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="myLogin">
		<div class="formLogin">
			<form action="" method="post">
				<h2>Sign up here</h2>

				<?php if( isset($error)):?>
				<div class="formError">
					<p>
						⛔️ Sorry, we can't log you in with that email address and password. Can you try again?
					</p>
				</div>
				<?php endif; ?>

				<div class="formField">
					<label id="email" for="email">Email</label>
					<input type="text" name="email">
				</div>
				<div class="formField">
					<label id="password" for="password">Password</label>
					<input type="password" name="password">
				</div>
				<div class="formField">
					<input type="submit" value="Sign in" class="btn">	
				</div>
				<div class=" label__inline">
  						<input type="checkbox" id="rememberMe">
 						<label for="rememberMe">Remember me</label>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use App\Hom\Users;

$error = false;

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $user = new Users(); // Create an instance of the Users class
        if ($user->canLogin($email, $password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header('Location: index.php');
            exit(); // Always exit after a header redirect
        } else {
            $error = "Invalid email or password.";
        }
    } catch (\Throwable $th) {
        $error = $th->getMessage(); // Catch and display any errors
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="myLogin">
		<div class="formLogin">
			<form action="" method="post">
				<h2>Login here</h2>

				<?php if($error !== false):?>
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
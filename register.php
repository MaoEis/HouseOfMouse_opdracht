<?php
include_once(__DIR__ . "/classes/Users.php");

if(!empty($_POST)){

	try{
		$user = new Users();
		$user->setEmail($_POST['email']);
		$user->setPassword($_POST['password']);

		$user->save();
		header('Location: login.php');

	} catch(\Throwable $th) {
		$error = $th->getMessage();
	}
	
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
					<input type="submit" value="Sign me up!" class="btn">	
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
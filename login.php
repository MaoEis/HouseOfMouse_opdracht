<php? 



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
				<h2 form__title>Login here</h2>

				<div class="formError">
					<p>
						⛔️ Sorry, we can't log you in with that email address and password. Can you try again?
					</p>
				</div>

				<div class="formField">
					<label for="Email">Email</label>
					<input type="text" name="email">
				</div>
				<div class="formField">
					<label for="Password">Password</label>
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
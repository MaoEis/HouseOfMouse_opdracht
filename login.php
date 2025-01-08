<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/Login.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $loginHandler = new LoginHandler();
    $result = $loginHandler->handleLogin($email, $password);

    // Handle result
    if ($result === 'adminIndex.php' || $result === 'index.php') {
        header("Location: $result");
        exit();
    } else {
        $error = $result;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body id="login">
    <div class="myLogin">
        <div class="formLogin">
            <form action="" method="post">
                <h2>Login here</h2>

                <?php if ($error): ?>
                <div class="formError">
                    <p>
                        ⛔️ <?= htmlspecialchars($error) ?>
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
                    <input type="submit" value="Sign in" class="btnLogin">    
                </div>
               <div class="label__inline">
  						<input type="checkbox" id="checkbox">
 						<label for="checkbox" class="label__inline">Remember me</label>
				</div>
                <div class="regInsteadField">
                    <a class="regInstead" href="register.php">No account? Register instead.</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
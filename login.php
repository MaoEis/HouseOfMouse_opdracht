<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/Db.php';
require_once __DIR__ . '/classes/Users.php';

$error = null;

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password cannot be empty.";
    } else {
        try {
            $user = new Users(); // Create an instance of the Users class
             $isAdmin = $user->canLogin($email, $password);
            if ($isAdmin !== false) {
                $_SESSION['loggedin'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['isAdmin'] = $isAdmin;

                if ($isAdmin == 'admin') { 
                    header('Location: adminIndex.php');
                } else {
                    header('Location: index.php');
                }
                exit(); 
            } else {
                $error = "Invalid email or password.";
            }
        } catch (\Throwable $th) {
            $error = $th->getMessage(); // Catch and display any errors
        }
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
            </form>
        </div>
    </div>
</body>
</html>
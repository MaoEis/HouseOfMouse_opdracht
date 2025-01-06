<?php 
session_start();
include_once('../classes/Users.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer of de gebruiker is ingelogd
    if (empty($_SESSION['email'])) {
        $error = [
            "status" => "error",
            "message" => "You are not logged in"
        ];
        echo json_encode($error);
        exit;
    }

    $oldPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Controleer of het nieuwe wachtwoord overeenkomt
    if ($newPassword !== $confirmPassword) {
        $error = [
            "status" => "error",
            "message" => "Passwords do not match",
            "error_div" => "confirm_password"
        ];
        echo json_encode($error);
        exit;
    }

    try {
        $user = new Users();
        $user->setEmail($_SESSION['email']); 

        $result = $user->changePassword($oldPassword, $newPassword);

        if ($result) {
            $success = [
                "status" => "success",
                "message" => "Password changed successfully"
            ];
            echo json_encode($success);
        }
        else{
            $error = [
                "status" => "error",
                "message" => "Het oude wachtwoord is onjuist.",
                "error_div" => "current_password"
            ];
            echo json_encode($error);
        }
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage()
        ];
        echo json_encode($error);
    }
} else {
    $error = [
        "status" => "error",
        "message" => "Invalid request method"
    ];
    echo json_encode($error);
}
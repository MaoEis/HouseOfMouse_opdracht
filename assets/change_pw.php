<?php 
session_start();
include_once('/../classes/Users.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (empty($_SESSION['email'])) {
        $error = [
            "status" => "error",
            "message" => "You are not logged in."
        ];
        echo json_encode($error);
        exit;
    }

    // Sanitize user input to avoid XSS
    $oldPassword = htmlspecialchars($_POST['current_password'], ENT_QUOTES, 'UTF-8');
    $newPassword = htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
    $confirmPassword = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8');

    // Password match check
    if ($newPassword !== $confirmPassword) {
        $error = [
            "status" => "error",
            "message" => "Passwords do not match.",
            "error_div" => "confirm_password"
        ];
        echo json_encode($error);
        exit;
    }


    try {
        $user = new Users();
        $user->setEmail($_SESSION['email']); 

        // Attempt to change the password
        $result = $user->changePassword($oldPassword, $newPassword);

        if ($result) {
            $success = [
                "status" => "success",
                "message" => "Password changed successfully."
            ];
            echo json_encode($success);
        }
    } catch (Exception $e) {
        $error = [
            "status" => "error",
            "message" => $e->getMessage(),
            "error_div" => "current_password"
        ];
        echo json_encode($error);
    }
} else {
    $error = [
        "status" => "error",
        "message" => "Invalid request method."
    ];
    echo json_encode($error);
}

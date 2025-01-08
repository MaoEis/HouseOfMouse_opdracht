<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . "/../classes/Db.php");
include_once(__DIR__ . "/../classes/Products.php");

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $product = new Products();

    try {
        // Set the new values from the form, properly sanitized
        $product->setId((int)$_POST['id']);
        $product->setTitle(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        $product->setDescription(htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8'));
        $product->setAmount((int)$_POST['amount']);
        $product->setCategoryId((int)$_POST['category_id']);
        $product->setPrice((float)$_POST['price']);
        $product->setHeight((float)$_POST['height']);
        $product->setDiameter((float)$_POST['diameter']);
        
        // File upload handling
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $fileName = basename($_FILES['file']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                $product->setFileName($fileName);
            } else {
                throw new Exception("File upload failed.");
            }
        }

        // Update the product
        if ($product->update()) {
            header("Location: ../productPageAdded.php?id=" . $product->getId());
            exit;
        } else {
            throw new Exception("Failed to update product.");
        }
    } catch (Exception $e) {
        // Log the error and show a user-friendly message
        error_log($e->getMessage());
        header("Location: ../myAdmin.php?error=" . urlencode("Unable to save product changes. Please try again."));
        exit;
    }
}
?>

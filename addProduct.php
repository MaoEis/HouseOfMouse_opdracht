<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// include_once(__DIR__ . "/classes/Db.php");
// include_once(__DIR__ . "/classes/Products.php");
// include_once(__DIR__ . "/classes/Upload.php");

// $uploadsDir = __DIR__ . '/uploads/';
// if (!is_dir($uploadsDir)) {
//     mkdir($uploadsDir, 0775, true); // Create the directory if it doesn't exist
//     echo "Uploads directory created.<br>";
// } else {
//     echo "Uploads directory already exists.<br>";
// }

// try {
//     // Fetch categories from the database
//     $conn = Db::getConnection();
    
//     // Fetch categories
//     $sql = "SELECT id, name FROM category";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Fetch colors
//     $sql = "SELECT id, name FROM colors";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Fetch materials
//     $sql = "SELECT id, name FROM materials";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// } catch (PDOException $e) {
//     echo "Error fetching data: " . $e->getMessage();
//     $categories = [];
//     $colors = [];
//     $materials = [];
// }

// if (isset($_POST['submit'])) {
//     echo "Form submitted.<br>";
//     try {
//         // Handle file upload
//         $file = $_FILES['file'];
//         $fileName = $_FILES['file']['name'];
//         $fileTmpName = $_FILES['file']['tmp_name'];
//         $fileSize = $_FILES['file']['size'];
//         $fileError = $_FILES['file']['error'];
//         $fileType = $_FILES['file']['type'];

//         $fileExt = explode('.', $fileName);
//         $fileActualExt = strtolower(end($fileExt));

//         $allowed = array('jpg', 'jpeg', 'png');

//         if (in_array($fileActualExt, $allowed)) {
//             if ($fileError === 0) {
//                 if ($fileSize < 1000000000) {
//                     $fileNameNew = uniqid('', true).".".$fileActualExt;
//                     $fileDestination = 'uploads/'.$fileNameNew;
//                     if (move_uploaded_file($fileTmpName, $fileDestination)) {
//                         echo "File uploaded successfully.<br>";

//                         $upload = new Upload();
//                         $upload->setFileName($fileNameNew)
//                                ->setFilePath($fileDestination)
//                                ->setFileSize($fileSize)
//                                ->setFileType($fileType);

//                         // Save the upload information to the database
//                         $uploadId = $upload->save();
//                         echo "Upload saved with ID: $uploadId<br>";

//                         // Create a new product instance
//                         $product = new Products();
//                         $product->setTitle($_POST['title'])
//                                 ->setDescription($_POST['description'])
//                                 ->setAmount($_POST['amount'])
//                                 ->setCategoryId($_POST['category_id'])
//                                 ->setPrice($_POST['price'])
//                                 ->setHeight($_POST['height'])
//                                 ->setDiameter($_POST['diameter'])
//                                 ->setUploadId($uploadId);  // Save the file path as picture

//                         // Save the product to the database
//                         if ($product->save()) {
//     echo "<script>window.productAdded = true;</script>";
//     echo "<script>window.productId = {$product->getId()};</script>";
// } else {
//     echo "Failed to add product.";
// }
//                     } else {
//                         echo "Failed to move uploaded file.";
//                     }
//                 } else {
//                     echo "Your file is too big!";
//                 }
//             } else {
//                 echo "There was an error uploading your file!";
//             }
//         } else {
//             echo "You cannot upload files of this type!";
//         }
//     } catch (Exception $e) {
//         echo $e->getMessage();
//     }
// }




error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Products.php");
include_once(__DIR__ . "/classes/Upload.php");

$uploadsDir = __DIR__ . '/uploads/';
// Controleer of de uploads-map bestaat en maak deze indien nodig aan

// Create directory if it does not exist
if (!is_dir($uploadsDir)) {
    try {
        if (!mkdir($uploadsDir, 0775, true)) {
            throw new Exception("Failed to create uploads directory.");
        }
        echo "Uploads directory created.<br>";
    } catch (Exception $e) {
        echo $e->getMessage() . "<br>";
    }
}

// // Set directory permissions
// if (is_dir($uploadsDir)) {
//     if (!chmod($uploadsDir, 0777)) {
//         echo "Failed to set directory permissions.<br>";
//     } else {
//         echo "Directory permissions set to 0777.<br>";
//     }
// } else {
//     echo "Uploads directory is not writable.<br>";
// }


try {
    // Haal categorieën, kleuren en materialen op uit de database
    $conn = Db::getConnection();

    // Haal categorieën op
    $sql = "SELECT id, name FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Haal kleuren op
    $sql = "SELECT id, name FROM colors";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Haal materialen op
    $sql = "SELECT id, name FROM materials";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error fetching data: " . $e->getMessage();
    $categories = [];
    $colors = [];
    $materials = [];
}

if (isset($_POST['submit'])) {
    echo "Form submitted.<br>";

    try {
        // Verwerk bestand upload
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileDestination = $uploadsDir . $fileNameNew;

                    // Controleer of de uploads-map schrijfbaar is
                    if (is_writable($uploadsDir)) {
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            echo "File uploaded successfully.<br>";

                            $upload = new Upload();
                            $upload->setFileName($fileNameNew)
                                ->setFilePath($fileDestination)
                                ->setFileSize($fileSize)
                                ->setFileType($fileType);

                            // Sla de uploadgegevens op in de database
                            $uploadId = $upload->save();
                            echo "Upload saved with ID: $uploadId<br>";

                            // Maak een nieuw product aan
                            $product = new Products();
                            $product->setTitle($_POST['title'])
                                ->setDescription($_POST['description'])
                                ->setAmount($_POST['amount'])
                                ->setCategoryId($_POST['category_id'])
                                ->setPrice($_POST['price'])
                                ->setHeight($_POST['height'])
                                ->setDiameter($_POST['diameter'])
                                ->setUploadId($uploadId);

                            // Sla het product op in de database
                            if ($product->save()) {
                                echo "<script>window.productAdded = true;</script>";
                                echo "<script>window.productId = {$product->getId()};</script>";
                            } else {
                                echo "Failed to add product.";
                            }
                        } else {
                            echo "Failed to move uploaded file. Check directory permissions.";
                        }
                    } else {
                        echo "Uploads directory is not writable. Check permissions.";
                    }
                } else {
                    echo "Your file is too big!";
                }
            } else {
                echo "There was an error uploading your file! Error code: $fileError";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new HouseOfMooseproduct</title>
    <script>
       document.addEventListener("DOMContentLoaded", function () {
    if (window.productAdded) {
        const successModal = document.getElementById("successModalAdd");

        // Show the modal
        successModal.style.display = "flex";

        // Redirect to product page
        document.getElementById("seeProductBtnAdd").addEventListener("click", function () {
            window.location.href = `productPageAdded.php?id=${window.productId}`;
        });

        // Add another product
        document.getElementById("addAnotherBtnAdd").addEventListener("click", function () {
            successModal.style.display = "none"; // Hide modal            
            window.location.href = `addProduct.php`;
        });

        // Close modal when clicking outside of it
        window.addEventListener("click", function (event) {
            if (event.target === successModal) {
                successModal.style.display = "none";
            }
        });
    }
});


    </script>
</head>
<body>
    <div>
         <?php include_once("navAdmin.inc.php"); ?>
        <h1 class="addProductTitle">
            Add new product
        </h1>
        <div class="newProductForm">
            <form class="addProductForm totalForm" action="" method="post"  enctype="multipart/form-data">
                <div class="addProductPicture">
                    <label class="addProductPictureLabel" for="picture">Picture:</label>
                    <input type="file" name="file" id="prodPic" required>
                </div>
                <div class="addProductFormInfo">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div>
                        <label for="description">Description:</label>
                        <input type="text" name="description" id="description" required>
                    </div>
                    <div>
                        <label for="amount">Amount:</label>
                        <input type="number" name="amount" id="amount" required min="0">
                    </div>
                    <div>
                        <label for="category">Category:</label>
                        <select name="category_id" id="category">
                            <?php foreach ($categories as $category): ?>
                            <option  value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                    </select>
                    </div>
                    <div>
                       <label for="color_id">Color:</label>
                       <select name="color_id" id="color_id" required>
                           <?php foreach ($colors as $color): ?>
                           <option value="<?php echo $color['id']; ?>"><?php echo $color['name']; ?></option>
                       <?php endforeach; ?>
                       </select>
                    </div>
                    <div>
                        <label for="material_id">Metal material:</label>
                        <select name="material_id" id="material_id" required>
                            <?php foreach ($materials as $material): ?>
                        <option value="<?php echo $material['id']; ?>"><?php echo $material['name']; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="price">Price:</label>
                        <input type="number" name="price" id="price" required min="0">
                    </div>
                    <div>
                        <label for="height">Height:</label>
                        <input type="number" name="height" id="height" required min="0">
                    </div>
                    <div>
                        <label for="diameter">Diameter:</label>
                        <input type="number" name="diameter" id="diameter" required min="0">
                    </div>
                    <input type="submit" name="submit" value="Add product">
                </div>
            </form>
        </div>
    </div>
    <div id="successModalAdd" class="modalAdd">
    <div class="modalContentAdd">
        <p>Product successfully added!</p>
        <div class="modalButtonsAdd">
            <button id="seeProductBtnAdd" class="modalBtnAdd">See Product</button>
            <button id="addAnotherBtnAdd" class="modalBtnAdd">Add Another Product</button>
        </div>
    </div>
                        </div>
</body>
</html>
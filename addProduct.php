<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Products.php");
include_once(__DIR__ . "/classes/Upload.php");

try {
    // Fetch categories from the database
    $conn = Db::getConnection();
    
    // Fetch categories
    $sql = "SELECT id, name FROM category";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch colors
    $sql = "SELECT id, name FROM colors";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch materials
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
        // Handle file upload
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) {
                    $fileNameNew = uniqid('', true).".".$fileActualExt;
                    $fileDestination = 'uploads/'.$fileNameNew;
                    if (move_uploaded_file($fileTmpName, $fileDestination)) {
                        echo "File uploaded successfully.<br>";

                        $upload = new Upload();
                        $upload->setFileName($fileNameNew)
                               ->setFilePath($fileDestination)
                               ->setFileSize($fileSize)
                               ->setFileType($fileType);

                        // Save the upload information to the database
                        $uploadId = $upload->save();
                        echo "Upload saved with ID: $uploadId<br>";

                        // Create a new product instance
                        $product = new Products();
                        $product->setTitle($_POST['title'])
                                ->setDescription($_POST['description'])
                                ->setAmount($_POST['amount'])
                                ->setCategoryId($_POST['category_id'])
                                ->setPrice($_POST['price'])
                                ->setHeight($_POST['height'])
                                ->setDiameter($_POST['diameter'])
                                ->setUploadId($uploadId);  // Save the file path as picture

                        // Save the product to the database
                        if ($product->save()) {
                            echo "Product added successfully!";
                        } else {
                            echo "Failed to add product.";
                        }
                    } else {
                        echo "Failed to move uploaded file.";
                    }
                } else {
                    echo "Your file is too big!";
                }
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Add new HouseOfMooseproduct</title>
</head>
<body>
    <div>
         <?php include_once("navAdmin.inc.php"); ?>
        <h1 class="addProductTitle">
            Add new product
        </h1>
        <div class="newProductForm">
            <form class="addProductForm" action="" method="post"  enctype="multipart/form-data">
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
                <div>
                    <label for="width">Width:</label>
                    <input type="number" name="width" id="width" required min="0">
                </div>
                <div>
                    <label for="picture">Picture:</label>
                    <input type="file" name="file" id="prodPic" required>
                    <!-- <input type="text" name="picture" id="picture" required> -->
                    <!-- <form action ="upload.php" method ="POST" enctype ="multipart/form-data">
                    <input type="file" name="file" id="filePic" required>
                    <button type="submit" name ="submit">btn</button> -->
                </div>
        <input type="submit" name="submit" value="Add product">
      </form>
        </div>
    </div>
</body>
</html>
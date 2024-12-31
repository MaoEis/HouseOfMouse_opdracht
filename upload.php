<?php 
include_once(__DIR__ . "/Db.php");
include_once(__DIR__ . "/Upload.php");

if (isset($_POST['submit'])) {
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
                $fileDestination = 'uploads.php/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                $upload = new Upload();
                $upload->setFileName($fileNameNew)
                       ->setFilePath($fileDestination)
                       ->setFileSize($fileSize)
                       ->setFileType($fileType);

                try {
                    $upload->save();
                    header("Location: addProduct.php?uploadsuccess");
                } catch (Exception $e) {
                    echo $e->getMessage();
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
}
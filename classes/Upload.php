<?php
include_once(__DIR__ . "/Db.php");

class Upload {
    protected $fileName;
    protected $filePath;
    protected $fileSize;
    protected $fileType;

    // fileName
    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName) {
        if(empty($fileName)) {
            throw new Exception('File name cannot be empty');
        }
        $this->fileName = $fileName;
        return $this;
    }

    // filePath
    public function getFilePath() {
        return $this->filePath;
    }

    public function setFilePath($filePath) {
        if(empty($filePath)) {
            throw new Exception('File path cannot be empty');
        }
        $this->filePath = $filePath;
        return $this;
    }

    // fileSize
    public function getFileSize() {
        return $this->fileSize;
    }

    public function setFileSize($fileSize) {
        if($fileSize <= 0) {
            throw new Exception('File size must be greater than zero');
        }
        $this->fileSize = $fileSize;
        return $this;
    }

    // fileType
    public function getFileType() {
        return $this->fileType;
    }

    public function setFileType($fileType) {
        if(empty($fileType)) {
            throw new Exception('File type cannot be empty');
        }
        $this->fileType = $fileType;
        return $this;
    }

   public function save() {
        try {
            // Database connection
            $conn = Db::getConnection();
            echo "Database connection established.<br>";

            // Insert upload information into the database
            $sql = "INSERT INTO uploads (fileName, filePath, fileSize, fileType) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $this->fileName);
            $stmt->bindParam(2, $this->filePath);
            $stmt->bindParam(3, $this->fileSize);
            $stmt->bindParam(4, $this->fileType);

            if ($stmt->execute()) {
                echo "Upload information saved successfully.<br>";
                return $conn->lastInsertId();
            } else {
                throw new Exception("Error: " . $stmt->errorInfo()[2]);
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage() . "<br>";
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}



<?php
include_once(__DIR__ . "/classes/Db.php");

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details from the database
    $conn = Db::getConnection();
    $query = $conn->prepare("
        SELECT products.*, uploads.fileName 
        FROM products 
        JOIN uploads ON products.upload_id = uploads.id 
        WHERE products.id = :id
    ");
    $query->bindParam(':id', $productId, PDO::PARAM_INT);
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Display product details
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($product['title']); ?></title>
        </head>
        <body>
            <div>
                <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($product['fileName']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="collectionImage">
            </div>
            <div>
                <h1><?php echo htmlspecialchars($product['title']); ?></h1>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Price: €<?php echo htmlspecialchars($product['price']); ?></p>
                <h3>Dimensions:</h3>
                <p>Height: <?php echo htmlspecialchars($product['height']); ?> cm</p>
                <p>Width: <?php echo htmlspecialchars($product['width']); ?> cm</p>
                <p>Diameter: <?php echo htmlspecialchars($product['diameter']); ?> cm</p>
                <a href="#">Add to bag</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Product not found.";
    }
} else {
    echo "No product ID specified.";
}
?>
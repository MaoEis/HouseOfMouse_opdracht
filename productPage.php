<?php
session_start();
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Reviews.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

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

} else {
    echo "No product ID specified.";
}

$product_id = $_GET['product_id']; // Assuming product_id is passed as a query parameter

try {
    $conn = Db::getConnection();
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $product_id);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $reviews = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $review = new Reviews();
        $review->setUserId($_SESSION['user_id'])
               ->setProductId($_POST['product_id'])
               ->setRating($_POST['rating'])
               ->setComment($_POST['comment']);

        if ($review->save()) {
            echo "Review submitted successfully.";
        } else {
            echo "Failed to submit review.";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
</head>
<body>
    <?php include_once("nav.inc.php"); ?>
    <div class="indivCollectPage">
        <div class="indivColImage">
            <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($product['fileName']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="collectionImage" id="indivColImage">
        </div>
        <div class="indivCollectInfo">
            <h1 class="indivProdTitle"><?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="indivProdDescr"><?php echo htmlspecialchars($product['description']); ?></p>
              <div class="ColMatSection">
                <h3 class="indivProdMat">COLOURS:</h3>
                <h3 class="indivProdMat">METAL MATERIAL:</h3>
                <!-- <p class="indivProdMat"><?php echo htmlspecialchars($product['materials']); ?></p> -->

            </div>
            <a class="addBagBtn" href="#">ADD TO BAG | €<?php echo htmlspecialchars($product['price']); ?></a>
             
            <div class="indivColDim">
                <h3 class="indivProdDimens">DIMENSIONS:</h3>
                <div class="indivColind">
                    <p class="indivProdHeight">Height: <?php echo htmlspecialchars($product['height']); ?> cm</p>
                    <p class="indivProdwidth">Width: <?php echo htmlspecialchars($product['width']); ?> cm</p>
                    <p class="indivProdDiam">Diameter: <?php echo htmlspecialchars($product['diameter']); ?> cm</p>
                </div>
            </div>
          
            <div class="reviewSection">
                <h3>REVIEWS:</h3>
                <div class="reviews">
                    <?php if (!empty($reviews)): ?>
    <?php foreach ($reviews as $review): ?>
        <div class="review">
            <p>User ID: <?php echo htmlspecialchars($review['user_id']); ?></p>
            <p>Rating: <?php echo htmlspecialchars($review['rating']); ?></p>
            <p>Comment: <?php echo htmlspecialchars($review['comment']); ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No reviews yet.</p>
<?php endif; ?>

                <h3>ADD REVIEW:</h3>
                
                <form class="reviewForm" action="#" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4" required /><label for="star4" title="4 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3" required /><label for="star3" title="3 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2" required /><label for="star2" title="2 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1" required /><label for="star1" title="1 star"></label>
                    </div>
                    <textarea name="comment" id="comment" required></textarea>
                    <button class="revBtn" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
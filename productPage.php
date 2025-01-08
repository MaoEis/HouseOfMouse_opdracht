<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Reviews.php");
include_once(__DIR__ . "/classes/Products.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId > 0) {
    $product = new Products();
    $productDetails = $product->getProductWithFileName($productId);
    if (!$productDetails) {
        die("Product not found.");
    }
} else {
    die("No valid product ID specified.");
}

try {
    $reviews = $product->getReviews($productId); // Correct method call
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $reviews = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $review = new Reviews();
        $review->setUserId($_SESSION['user_id'])
               ->setProductId($productId)
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title><?php echo htmlspecialchars($productDetails['title']); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/reviewForm.js"></script>
</head>
<body>
    <script src="js/productPage.js"></script>
    <?php include_once("nav.inc.php"); ?>
    <div class="indivCollectPage">
        <div class="indivColImage">
            <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($productDetails['fileName']); ?>" alt="<?php echo htmlspecialchars($productDetails['title']); ?>" class="collectionImage" id="indivColImage">
        </div>
        <div class="indivCollectInfo">
            <h1 class="indivProdTitle"><?php echo htmlspecialchars($productDetails['title']); ?></h1>
            <p class="indivProdDescr"><?php echo htmlspecialchars($productDetails['description']); ?></p>
              <div class="ColMatSection">
            </div>
            <a class="addBagBtn" href="#">ADD TO BAG | €<?php echo htmlspecialchars($productDetails['price']); ?></a>
             
            <div class="indivColDim">
                <h3 class="indivProdDimens">DIMENSIONS:</h3>
                <div class="indivColind">
                    <p class="indivProdHeight">Height: <?php echo htmlspecialchars($productDetails['height']); ?> cm</p>
                    <p class="indivProdDiam">Diameter: <?php echo htmlspecialchars($productDetails['diameter']); ?> cm</p>
                </div>
            </div>
          
            <div class="reviewSection">
                <h3>REVIEWS:</h3>
                <div class="reviews">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review">
                                <p class="userRev">User<?php echo htmlspecialchars($review['user_id']); ?></p>
                                <p class="ratingRev">Rating: <?php echo htmlspecialchars($review['rating']); ?>x ⭐️</p>
                                <p class="commentRev"><?php echo htmlspecialchars($review['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reviews yet.</p>
                    <?php endif; ?>
                </div>
                
                <h3>ADD REVIEW:</h3>
                <form class="reviewForm" id="reviewForm" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
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

                <div id="reviewMessage"></div>
            </div>
        </div>
    </div>

</body>
</html>

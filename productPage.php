<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

     if (!$product) {
        die("Product not found.");
    }
} else {
    echo "No product ID specified.";
}

try {
    $conn = Db::getConnection();
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT); // Correct variable is $productId
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

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId > 0) {
    $query = $conn->prepare("
        SELECT products.*, uploads.fileName 
        FROM products 
        JOIN uploads ON products.upload_id = uploads.id 
        WHERE products.id = :id
    ");
    $query->bindParam(':id', $productId, PDO::PARAM_INT);
    $query->execute();
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("No valid product ID specified.");
}

$user_id = $_SESSION['user_id'];

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.querySelector('.reviewForm');
    
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally

            let formData = new FormData(this);

            fetch('assets/submit_review.php', { // Make sure this path is correct
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                const reviewMessage = document.getElementById('reviewMessage');
                if (data.success) {
                    // If review is successfully added, show the success message
                    reviewMessage.innerHTML = "<p>Review submitted successfully.</p>";
                    
                    // Dynamically update reviews section with the new HTML
                    document.querySelector('.reviews').innerHTML = data.reviewsHtml;
                    document.getElementById('comment').value = '';
                     const stars = document.querySelectorAll('.star-rating input[type="radio"]');
                    stars.forEach(star => star.checked = false);
                } else {
                    reviewMessage.innerHTML = "<p>Failed to submit review. Please try again.</p>";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('reviewMessage').innerHTML = "<p>Something went wrong. Please try again.</p>";
            });
        });
    }
});
</script>

    <?php include_once("nav.inc.php"); ?>
    <div class="indivCollectPage">
        <div class="indivColImage">
            <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($product['fileName']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="collectionImage" id="indivColImage">
        </div>
        <div class="indivCollectInfo">
            <h1 class="indivProdTitle"><?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="indivProdDescr"><?php echo htmlspecialchars($product['description']); ?></p>
              <div class="ColMatSection">
                <!-- <h3 class="indivProdMat">COLOURS:</h3>
                <h3 class="indivProdMat">METAL MATERIAL:</h3> -->
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
                                <p class="userRev">User<?php echo htmlspecialchars($review['user_id']); ?></p>
                                <p class="ratingRev">Rating: <?php echo htmlspecialchars($review['rating']); ?></p>
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
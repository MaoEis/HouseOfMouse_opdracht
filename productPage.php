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
        // Include the HTML template and pass the product data
        include 'productTemplate.php';
    } else {
        echo "Product not found.";
    }
} else {
    echo "No product ID specified.";
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
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <div class="star-rating">
                                <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                    <span class="star">★</span>
                                <?php endfor; ?>
                            </div>
                            <p class="comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>

                <h3>ADD REVIEW:</h3>
                <form class="reviewForm" action="submit_review.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars">5</label>
                        <input type="radio" id="star4" name="rating" value="4" required /><label for="star4" title="4 stars">4</label>
                        <input type="radio" id="star3" name="rating" value="3" required /><label for="star3" title="3 stars">3</label>
                        <input type="radio" id="star2" name="rating" value="2" required /><label for="star2" title="2 stars">2</label>
                            <input type="radio" id="star1" name="rating" value="1" required /><label for="star1" title="1 star">1</label>
                    </div>
                    <textarea name="comment" id="comment" required></textarea>
                    <button class="revBtn" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
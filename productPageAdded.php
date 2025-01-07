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

    // Fetch product details
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
    die("No product ID specified.");
}

// Fetch reviews
try {
    $conn = Db::getConnection();
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $productId, PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $reviews = [];
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Delete query
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $productId, PDO::PARAM_INT);
if ($stmt->execute()) {
    echo 'success';
    exit();
} else {
    echo 'error';
    exit();
}
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
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
$(document).ready(function() {
    function showCustomModal(message) {
        $('#modalMessage').text(message);
        $('#customModal').fadeIn();

        // Close modal when clicking the close button
        $('#closeModal').click(function() {
            $('#customModal').fadeOut();
        });

        // Close modal when clicking outside the modal content
        $(window).click(function(event) {
            if ($(event.target).is('#customModal')) {
                $('#customModal').fadeOut();
            }
        });
    }

    let productIdToDelete = null;

    function showConfirmModal(callback) {
        $('#confirmModal').fadeIn();

        // Handle Yes button
        $('#confirmYes').off().click(function() {
            $('#confirmModal').fadeOut();
            if (callback) callback(true);
        });

        // Handle No button
        $('#confirmNo').off().click(function() {
            $('#confirmModal').fadeOut();
            if (callback) callback(false);
        });

        // Close modal when clicking outside the modal content
        $(window).click(function(event) {
            if ($(event.target).is('#confirmModal')) {
                $('#confirmModal').fadeOut();
                if (callback) callback(false);
            }
        });
    }

    $('.adminDel').click(function() {
        productIdToDelete = $(this).data('product-id');
        console.log("Delete button clicked, product ID:", productIdToDelete);

        showConfirmModal(function(isConfirmed) {
            if (isConfirmed) {
                $.ajax({
                    url: 'adminIndex.php',
                    type: 'POST',
                    data: { id: productIdToDelete, action: 'delete' },
                    success: function(response) {
                        console.log("AJAX request successful, response:", response);
                        if (response.trim() === 'success') {
                            showCustomModal('Product deleted successfully.');
                            $('[data-product-id="' + productIdToDelete + '"]').closest('.collectionItem').remove();
                        } else {
                            showCustomModal('Failed to delete the product. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", xhr.responseText, status, error);
                        showCustomModal('An error occurred. Please try again later.');
                    }
                });
            } else {
                console.log("User canceled the deletion.");
            }
        });
    });
});
    </script>
    <?php include_once("navAdmin.inc.php"); ?>
    <div class="indivCollectPage">
        <div class="indivColImage">
            <img src="/HouseOfMoose_opdracht/uploads/<?php echo htmlspecialchars($product['fileName']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="collectionImage" id="indivColImage">
        </div>
        <div class="indivCollectInfo">
            <h1 class="indivProdTitle"><?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="indivProdDescr"><?php echo htmlspecialchars($product['description']); ?></p>

            <div class="adminIndexBtns" id="adminBtns">
            <button class="indexAddBtnAdmin adminEdit">
              <a class="indexAddBtnAdmin"  href="myAdmin.php?id=<?php echo $c['id']; ?>">EDIT</a>
            </button>
            <button class="indexAddBtnAdmin adminDel" data-product-id="<?php echo $c['id']; ?>"> DELETE </button>
        </div>

              <div class="ColMatSection">
                <h3 class="indivProdMat">COLOURS:</h3>
                <h3 class="indivProdMat">METAL MATERIAL:</h3>
                <!-- <p class="indivProdMat"><?php echo htmlspecialchars($product['materials']); ?></p> -->

            </div>
          
             
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
            <p>User: <?php echo htmlspecialchars($review['user_id']); ?></p>
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
<div id="confirmModal" class="modal">
  <div class="modalContent">
    <p id="confirmMessage">Are you sure you want to delete this product?</p>
    <div class="modalButtons">
      <button id="confirmYes" class="confirmBtn">Yes</button>
      <button id="confirmNo" class="confirmBtn">No</button>
    </div>
  </div>
</div>
<div id="customModal" class="modal">
  <div class="modalContent">
    <span id="closeModal" class="closeBtn">&times;</span>
    <p id="modalMessage"></p>
  </div>
</div>
</body>
</html>
<?php
session_start();
include_once(__DIR__ . "/../classes/Db.php");
include_once(__DIR__ . "/../classes/Reviews.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent XSS
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_SPECIAL_CHARS);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($_SESSION['user_id']) && !empty($productId) && !empty($rating) && !empty($comment)) {
        try {
            $review = new Reviews();
            $review->setUserId($_SESSION['user_id'])
                   ->setProductId($productId)
                   ->setRating($rating)
                   ->setComment($comment);

            if ($review->save()) {
                // Fetch the updated reviews
                $conn = Db::getConnection();
                $sql = "SELECT * FROM reviews WHERE product_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $productId);
                $stmt->execute();
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Generate the HTML for the reviews section
                $reviewsHtml = '';
                foreach ($reviews as $review) {
                    $reviewsHtml .= '
                        <div class="review">
                            <p class="userRev">User ID: ' . htmlspecialchars($review['user_id']) . '</p>
                            <p class="ratingRev">Rating: ' . htmlspecialchars($review['rating']) . '</p>
                            <p class="commentRev">Comment: ' . htmlspecialchars($review['comment']) . '</p>
                        </div>';
                }

                echo json_encode([
                    'success' => true,
                    'reviewsHtml' => $reviewsHtml
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save review.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

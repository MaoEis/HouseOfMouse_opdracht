<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/Products.php");

// Check if ID is set in the query parameters
if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}

$productId = $_GET['id'];

// Fetch product data from the database
$product = new Products();
$productDetails = $product->getProductWithFileName($productId);

if (!$productDetails) {
    die("Product not found.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <?php include_once("navAdmin.inc.php"); ?>
    <h1>Edit Product</h1>
    <div class="newProductForm">
        <form class="addProductForm totalForm" action="assets/save_edit_product.php" method="post" enctype="multipart/form-data">
            <!-- Hidden field to store the product ID -->
            <input type="hidden" name="id" value="<?php echo $productDetails['id']; ?>">
            
            <!-- Pre-fill the form with the current product data -->
            <div class="addProductPicture">
                <label for="picture">Picture:</label>
                <input type="file" name="file" id="prodPic">
                <img src="uploads/<?php echo $productDetails['fileName'] ?? 'placeholder.jpg'; ?>" alt="Current product image" width="100">
            </div>
            <div class="addProductFormInfo">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($productDetails['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div>
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($productDetails['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div>
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($productDetails['stockAmount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required min="0">
                </div>
                <div>
                    <label for="category">Category:</label>
                    <select name="category_id" id="category">
                        <!-- Populate categories from DB -->
                        <?php
                        $categories = $product->getCategories();
                        foreach ($categories as $category) {
                            echo "<option value='{$category['id']}' " . ($category['id'] == $productDetails['category_id'] ? 'selected' : '') . ">{$category['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div>
                    <label for="color_id">Color:</label>
                    <select name="color_id" id="color_id">
                        <!-- Populate colors from DB -->
                        <?php
                        $colors = $product->getColors();
                        foreach ($colors as $color) {
                            echo "<option value='{$color['id']}' " . ($color['id'] == $productDetails['color_id'] ? 'selected' : '') . ">{$color['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
    
                <div>
                    <label for="material_id">Metal material:</label>
                    <select name="material_id" id="material_id">
                        <!-- Populate materials from DB -->
                        <?php
                        $materials = $product->getMaterials();
                        foreach ($materials as $material) {
                            echo "<option value='{$material['id']}' " . ($material['id'] == $productDetails['material_id'] ? 'selected' : '') . ">{$material['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($productDetails['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required min="0">
                </div>
                <div>
                    <label for="height">Height:</label>
                    <input type="number" name="height" id="height" value="<?php echo htmlspecialchars($productDetails['height'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required min="0">
                </div>
                <div>
                    <label for="diameter">Diameter:</label>
                    <input type="number" name="diameter" id="diameter" value="<?php echo htmlspecialchars($productDetails['diameter'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required min="0">
                </div>
                <div>
                    <label for="width">Width:</label>
                    <input type="number" name="width" id="width" value="<?php echo htmlspecialchars($productDetails['width'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required min="0">
                </div>
                <input type="submit" name="submit" value="Save changes">
            </div>
        </form>
    </div>
</body>
</html>

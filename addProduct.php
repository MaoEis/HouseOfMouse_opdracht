<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Add new HouseOfMooseproduct</title>
</head>
<body>
    <div>
         <?php include_once("navAdmin.inc.php"); ?>
        <h1 class="addProductTitle">
            Add new product
        </h1>
        <div class="newProductForm">
            <form class="addProductForm" action="" method="post">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div>
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" required>
                </div>
                <div>
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" id="amount" required min="0">
                </div>
                <div>
                    <label for="category">Category:</label>
                    <input type="text" name="category" id="category" required>
                </div>
                <div>
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" required min="0">
                </div>
                <div>
                    <label for="height">Height:</label>
                    <input type="number" name="height" id="height" required min="0">
                </div>
                <div>
                    <label for="diameter">Diameter:</label>
                    <input type="number" name="diameter" id="diameter" required min="0">
                </div>
                <div>
                    <label for="picture">Picture: </label>
                    <input type="text" name="picture" id="picture" required>
                </div>
                
        <input type="submit" value="Add product">
      </form>
        </div>
    </div>
</body>
</html>
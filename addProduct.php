<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Add a new HouseOfMooseproduct</title>
</head>
<body>
    <div>
         <?php include_once("navAdmin.inc.php"); ?>
        <h1>
            New House Of Moose product
        </h1>
        <form class="addProduct_Form" action="" method="post">
            <div>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="description">Description</label>
                <input type="text" name="description" id="description" required>
            </div>
            <div>
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div>
                <label for="category">Category</label>
                <input type="text" name="category" id="category" required>
            </div>
            <div>
                <label for="price">Price</label>
                <input type="number" name="price" id="price" required>
            </div>
            <div>
                <label for="height">Height</label>
                <input type="number" name="height" id="height" required>
            </div>
            <div>
                <label for="diameter">Diameter</label>
                <input type="number" name="diameter" id="diameter" required>
            </div>
            <div>
                <label for="picture">Picture</label>
                <input type="text" name="picture" id="picture" required>
            </div>
            <button type="submit">Add product</button>
        </form>
    </div>
</body>
</html>
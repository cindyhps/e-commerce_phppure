<?php
include('../includes/connect.php');

if(isset($_POST['insert_product'])){
    $product_title = $_POST['product_title'];
    $product_description = $_POST['product_description'];
    $product_keywords = $_POST['product_keywords'];
    $product_category = $_POST['product_category'];
    $product_brands = $_POST['product_brands'];
    $product_price = $_POST['product_price'];
    $product_status = "true";
    
    // Accessing image
    $product_image = $_FILES['product_image']['name'];
    $temp_image = $_FILES['product_image']['tmp_name'];
    
    // Checking empty condition
    if(empty($product_title) || empty($product_description) || empty($product_keywords) || 
    empty($product_category) || empty($product_brands) || empty($product_price) || 
    empty($product_image)){
        echo "<script>alert('Please fill all the available fields')</script>";
        exit();
    } else {
        move_uploaded_file($temp_image, "../admin_area/product_images/$product_image");

        // Insert query using prepared statements
        $insert_products = "INSERT INTO products (product_title, product_description, product_keywords, category_id, brand_id, product_image, product_price, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

        if ($stmt = mysqli_prepare($con, $insert_products)) {
            mysqli_stmt_bind_param($stmt, "sssissis", $product_title, $product_description, $product_keywords, $product_category, $product_brands, $product_image, $product_price, $product_status);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt) > 0){
                echo "<script>alert('Product has been added')</script>";
            } else {
                echo "<script>alert('Error: Could not add product')</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Error: Could not prepare statement')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../public/view/css/style.css">
    <link rel="stylesheet" href="../public/view/js/main.js">
</head>
<body class="bg-light">
    <div class="container mt-3"> 
        <h1 class="text-center">Insert Products</h1>
        <!-- Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Product Title -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_title" class="form-label">Product Title</label>
                <input type="text" name="product_title" id="product_title" class="form-control" placeholder="Enter Product's Name" autocomplete="off" required>
            </div>

            <!-- Description -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_description" class="form-label">Product's Description</label>
                <input type="text" name="product_description" id="product_description" class="form-control" placeholder="Enter Product's Description" autocomplete="off" required>
            </div>

            <!-- Product Keywords -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_keywords" class="form-label">Product's Keywords</label>
                <input type="text" name="product_keywords" id="product_keywords" class="form-control" placeholder="Enter Product's Keywords" autocomplete="off" required>
            </div>
            
            <!-- Category -->
            <div class="form-outline mb-4 w-50 m-auto">
                <select name="product_category" id="product_category" class="form-select" required>
                    <option value="">Select a Category</option>
                    <?php
                        $select_query = "SELECT * FROM categories";
                        $result_query = mysqli_query($con, $select_query);
                        while($row = mysqli_fetch_assoc($result_query)){
                            $category_title = $row['category_title'];
                            $category_id = $row['category_id'];
                            echo "<option value='$category_id'>$category_title</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Brands -->
            <div class="form-outline mb-4 w-50 m-auto">
                <select name="product_brands" id="product_brands" class="form-select" required>
                    <option value="">Select a Brand</option>
                    <?php
                        $select_query = "SELECT * FROM brands";
                        $result_query = mysqli_query($con, $select_query);
                        while($row = mysqli_fetch_assoc($result_query)){
                            $brand_name = $row['brand_name'];
                            $brand_id = $row['brand_id'];
                            echo "<option value='$brand_id'>$brand_name</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Image -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image" class="form-label">Product's Image</label>
                <input type="file" name="product_image" id="product_image" class="form-control" required>
            </div>
            
            <!-- Product Price -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="text" name="product_price" id="product_price" class="form-control" placeholder="Enter Product's Price" autocomplete="off" required>
            </div>

            <!-- Submit Button -->
            <div class="form-outline mb-4 w-50 m-auto">
                <input type="submit" name="insert_product" value="Insert New Data Products" class="btn btn-info mb-3 px-3">
            </div>
        </form>
                <!-- Navigation back to Admin Dashboard -->
                <div class="text-center mt-4">
            <a href="index_admin.php" class="btn btn-secondary">Back to Admin Dashboard</a>
        </div>
    </div>

    </div>
</body>
</html>

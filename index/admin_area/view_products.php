<?php
include('../includes/connect.php');

// Delete product
if(isset($_GET['delete'])){
    $product_id = $_GET['delete'];
    $delete_query = "DELETE FROM products WHERE product_id = ?";
    if ($stmt = mysqli_prepare($con, $delete_query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Product has been deleted');</script>";
        mysqli_stmt_close($stmt);
        // Redirect to prevent re-submission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// Update product
if(isset($_POST['update_product'])){
    $product_id = $_POST['product_id'];
    $product_title = $_POST['product_title'];
    $product_description = $_POST['product_description'];
    $product_keywords = $_POST['product_keywords'];
    $product_category = $_POST['product_category'];
    $product_brands = $_POST['product_brands'];
    $product_price = $_POST['product_price'];
    $product_status = "true";
    
    $update_query = "UPDATE products SET product_title=?, product_description=?, product_keywords=?, category_id=?, brand_id=?, product_price=?, status=? WHERE product_id=?";
    if ($stmt = mysqli_prepare($con, $update_query)) {
        mysqli_stmt_bind_param($stmt, "sssisssi", $product_title, $product_description, $product_keywords, $product_category, $product_brands, $product_price, $product_status, $product_id);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('Product has been updated');</script>";
        mysqli_stmt_close($stmt);
        // Redirect to prevent re-submission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// Search products
if(isset($_POST['search_product'])){
    $search_term = $_POST['search_term'];
    $search_query = "SELECT * FROM products WHERE product_title LIKE ?";
    if ($stmt = mysqli_prepare($con, $search_query)) {
        $search_term = "%{$search_term}%";
        mysqli_stmt_bind_param($stmt, "s", $search_term);
        mysqli_stmt_execute($stmt);
        $result_query = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error: Could not prepare statement');</script>";
    }
} else {
    // Default query to fetch all products
    $select_query = "SELECT * FROM products";
    $result_query = mysqli_query($con, $select_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/view/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <h1 class="text-center">Manage Products</h1>

        <!-- Search form -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Product" name="search_term">
                <button class="btn btn-outline-secondary" type="submit" name="search_product">Search</button>
            </div>
        </form>

        <!-- Display products -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Keywords</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($result_query)){
                    while($row = mysqli_fetch_assoc($result_query)){
                        echo "<tr>
                            <td>{$row['product_id']}</td>
                            <td>{$row['product_title']}</td>
                            <td>{$row['product_description']}</td>
                            <td>{$row['product_keywords']}</td>
                            <td>{$row['category_id']}</td>
                            <td>{$row['brand_id']}</td>
                            <td>{$row['product_price']}</td>
                            <td><img src='../admin_area/product_images/{$row['product_image']}' width='50' height='50'></td>
                            <td>
                                <a href='view_products.php?edit={$row['product_id']}' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='view_products.php?delete={$row['product_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Update product form -->
        <?php
        if(isset($_GET['edit'])){
            $product_id = $_GET['edit'];
            $select_query = "SELECT * FROM products WHERE product_id = ?";
            if ($stmt = mysqli_prepare($con, $select_query)) {
                mysqli_stmt_bind_param($stmt, "i", $product_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                ?>
                <h2 class="text-center">Edit Product</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <div class="form-group mb-3">
                        <label for="product_title">Product Title</label>
                        <input type="text" name="product_title" id="product_title" class="form-control" value="<?php echo $row['product_title']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="product_description">Product Description</label>
                        <input type="text" name="product_description" id="product_description" class="form-control" value="<?php echo $row['product_description']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="product_keywords">Product Keywords</label>
                        <input type="text" name="product_keywords" id="product_keywords" class="form-control" value="<?php echo $row['product_keywords']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="product_category">Category</label>
                        <select name="product_category" id="product_category" class="form-control" required>
                            <?php
                            $select_query = "SELECT * FROM categories";
                            $result_query = mysqli_query($con, $select_query);
                            while($category = mysqli_fetch_assoc($result_query)){
                                $selected = ($category['category_id'] == $row['category_id']) ? 'selected' : '';
                                echo "<option value='{$category['category_id']}' $selected>{$category['category_title']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="product_brands">Brand</label>
                        <select name="product_brands" id="product_brands" class="form-control" required>
                            <?php
                            $select_query = "SELECT * FROM brands";
                            $result_query = mysqli_query($con, $select_query);
                            while($brand = mysqli_fetch_assoc($result_query)){
                                $selected = ($brand['brand_id'] == $row['brand_id']) ? 'selected' : '';
                                echo "<option value='{$brand['brand_id']}' $selected>{$brand['brand_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="product_price">Product Price</label>
                        <input type="text" name="product_price" id="product_price" class="form-control" value="<?php echo $row['product_price']; ?>" required>
                    </div>
                    <button type="submit" name="update_product" class="btn btn-info">Update Product</button>
                </form>
                <?php
                mysqli_stmt_close($stmt);
            }
        }
        ?>
    <a href="index_admin.php" class="btn btn-secondary">Back to Admin Dashboard</a>
        <!-- Navigation back to Admin Dashboard -->

    </div>

    <!-- Footer -->
    <div class="footer text-center">
        <p>&copy; Copyright@Tim Saya 2024</p>
    </div>
</body>
</html>

<?php
// include database connection file
include('../includes/connect.php');

// Check if category ID parameter exists in URL
if(isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch category details from database
    $query = "SELECT * FROM categories WHERE category_id = '$category_id'";
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $category_title = $row['category_title'];
    } else {
        echo "<script>alert('Category not found')</script>";
        echo "<script>window.location.href='view_categories.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request')</script>";
    echo "<script>window.location.href='view_categories.php';</script>";
    exit();
}

// Handle Update Request
if(isset($_POST['update'])) {
    $new_category_title = mysqli_real_escape_string($con, $_POST['category_title']);
    $update_query = "UPDATE categories SET category_title = '$new_category_title' WHERE category_id = '$category_id'";
    $update_result = mysqli_query($con, $update_query);

    if($update_result) {
        echo "<script>alert('Category updated successfully')</script>";
        echo "<script>window.location.href='view_categories.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update category')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Category</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="category_title">Category Title</label>
                <input type="text" class="form-control" id="category_title" name="category_title" value="<?php echo htmlspecialchars($category_title); ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Category</button>
            <a href="view_categories.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    
</body>
</html>

<?php
// Include database connection
include('../includes/connect.php');

// Function to sanitize input
function sanitize($con, $input) {
    return mysqli_real_escape_string($con, $input);
}

// Check if ID parameter exists
if(isset($_GET['id'])) {
    $brand_id = sanitize($con, $_GET['id']);

    // Fetch brand details from database
    $select_query = "SELECT * FROM brands WHERE brand_id = '$brand_id'";
    $result_select = mysqli_query($con, $select_query);

    if(mysqli_num_rows($result_select) > 0) {
        $row = mysqli_fetch_assoc($result_select);
        $brand_name = $row['brand_name'];
    } else {
        // Jika tidak ada data merek dengan ID yang diberikan, bisa di-handle sesuai kebutuhan.
        echo "Merek tidak ditemukan.";
        exit;
    }
} else {
    echo "ID Merek tidak diberikan.";
    exit;
}

// Process form submission
if(isset($_POST['update'])) {
    $new_brand_name = sanitize($con, $_POST['brand_name']);
    
    // Update query
    $update_query = "UPDATE brands SET brand_name = '$new_brand_name' WHERE brand_id = '$brand_id'";
    $result_update = mysqli_query($con, $update_query);

    if($result_update) {
        echo "<script>alert('Brand updated successfully')</script>";
        // Redirect back to view_brands.php after update
        echo "<script>window.location.href = 'view_brands.php';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to update brand')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Brand</h2>
        <form method="post">
            <div class="form-group">
                <label for="brand_name">Brand Name:</label>
                <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo $brand_name; ?>">
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Brand</button>
            <a href="view_brands.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($con);
?>

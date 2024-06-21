<?php
// include database connection file
include('../includes/connect.php');

// Handle Delete Request
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM categories WHERE category_id = '$delete_id'";
    $result = mysqli_query($con, $delete_query);
    if($result) {
        echo "<script>alert('Category Deleted Successfully')</script>";
        echo "<script>window.location.href='view_categories.php';</script>";
    } else {
        echo "<script>alert('Failed to Delete Category')</script>";
    }
}

// Handle Update Request
if(isset($_POST['update_cat'])) {
    $update_id = $_POST['cat_id'];
    $category_title = mysqli_real_escape_string($con, $_POST['cat_title']);
    $update_query = "UPDATE categories SET category_title = '$category_title' WHERE id = '$update_id'";
    $result = mysqli_query($con, $update_query);
    if($result) {
        echo "<script>alert('Category Updated Successfully')</script>";
        echo "<script>window.location.href='view_categories.php';</script>";
    } else {
        echo "<script>alert('Failed to Update Category')</script>";
    }
}

// Handle Search
$search = '';
if(isset($_POST['search'])) {
    $search = mysqli_real_escape_string($con, $_POST['search']);
    $search_query = "SELECT * FROM categories WHERE category_title LIKE '%$search%'";
} else {
    $search_query = "SELECT * FROM categories";
}
$result = mysqli_query($con, $search_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>View Categories</h2>
        <!-- Search Form -->
        <form action="" method="post" class="form-inline mb-3">
            <input type="text" class="form-control mr-2" name="search" placeholder="Search Categories" value="<?php echo $search; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Categories Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$row['category_id']."</td>";
                        echo "<td>".$row['category_title']."</td>";
                        echo "<td>
                                <a href='edit_category.php?id=".$row['category_id']."' class='btn btn-info btn-sm mr-2'>Edit</a>
                                <a href='view_categories.php?delete_id=".$row['category_id']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this category?')\">Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No categories found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
            <!-- Navigation back to Admin Dashboard -->
            <div class="text-center mt-4">
            <a href="index_admin.php" class="btn btn-secondary">Back to Admin Dashboard</a>
        </div>
    </div>

</body>
</html>

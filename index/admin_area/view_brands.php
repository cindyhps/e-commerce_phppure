<?php
// Include database connection
include('../includes/connect.php');

// Function to sanitize input
function sanitize($con, $input) {
    return mysqli_real_escape_string($con, $input);
}

// Process delete action
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $brand_id = sanitize($con, $_GET['id']);
    $delete_query = "DELETE FROM brands WHERE brand_id = '$brand_id'";
    $result_delete = mysqli_query($con, $delete_query);
    if($result_delete) {
        echo "<script>alert('Brand deleted successfully')</script>";
    } else {
        echo "<script>alert('Failed to delete brand')</script>";
    }
}

// Process search action
$search_query = "";
if(isset($_POST['search'])) {
    $search = sanitize($con, $_POST['search']);
    $search_query = "WHERE brand_name LIKE '%$search%'";
}

// Fetch brands from database
$select_query = "SELECT * FROM brands $search_query";
$result_select = mysqli_query($con, $select_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Brands</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">View Brands</h2>

        <!-- Search form -->
        <form method="post" class="form-inline mb-4">
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="search" placeholder="Search Brands">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Table to display brands -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while($row = mysqli_fetch_assoc($result_select)) {
                    echo "<tr>";
                    echo "<td>{$count}</td>";
                    echo "<td>{$row['brand_name']}</td>";
                    echo "<td>
                            <a href='edit_brands.php?id={$row['brand_id']}' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i> Edit</a>
                            <a href='view_brands.php?action=delete&id={$row['brand_id']}' class='btn btn-sm btn-danger'><i class='bi bi-trash'></i> Delete</a>
                          </td>";
                    echo "</tr>";
                    $count++;
                }
                ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="index_admin.php" class="btn btn-secondary">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php
// Close database connection
mysqli_close($con);
?>

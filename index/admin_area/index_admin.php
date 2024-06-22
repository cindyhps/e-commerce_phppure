<?php
session_start();

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Arahkan ke halaman login jika belum login
    exit;
}

// Inisialisasi variabel user_name dari session
$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS Bundle, Font API Google, Font Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Import File CSS JS -->
    <link rel="stylesheet" href="../public/view/css/style.css"> 
    <style>
    .admin_img{
    width: 100px;
    object-fit: contain;
    }
    .footer{
        position:absolute;
        bottom:0;
    }
    </style>
    <link rel="stylesheet" href="../public/view/js/main.js">

</head>
<body>
    <!-- Navbar -->
    <div class="container-fluid p-0">
        <!-- 1st Child -->
        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">
                <img src="../public/view/css/assets/logo.png" alt="" class="logo_admin">
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                        <span class="nav-link">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>

        <!-- 2nd Child -->
        <div class="bg-light">
            <h3 class="text-center p-2">Manage Details</h3>
        </div>

        <!-- 3rd Child -->
        <div class="row">
            <div class="col-md-12 bg-secondary p-1 d-flex align-items-center">
                <div class="p-3">

                            
                </div>
                <!-- Button Admin Manipulate Data -->
                <div class="button text-center m-auto">
                    <button class="my-3">
                        <a href="insert_products.php" class="nav-link text-light bg-info my-1">Insert Products</a>
                    </button>
                    <button>
                        <a href="view_products.php" class="nav-link text-light bg-info my-1">View Products</a>
                    </button>

                    <button>
                        <a href="index_admin.php?insert_category" class="nav-link text-light bg-info my-1">Insert Categories</a>
                    </button>
                    <button>
                        <a href="view_categories.php" class="nav-link text-light bg-info my-1">View Categories</a>
                    </button>
                    <button>
                        <a href="index_admin.php?insert_brand" class="nav-link text-light bg-info my-1">Insert Brands</a>
                    </button>
                    <button>
                        <a href="view_brands.php" class="nav-link text-light bg-info my-1">View Brands</a>
                    </button>

                    <button>
                        <a href="list_user.php" class="nav-link text-light bg-info my-1">List Users</a>
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="window.location.href='../index.php'">Logout</button>
                </div>
            </div>
        </div>

        <!-- 4th Child -->
        <div class="container my-5">
            <?php 
            if(isset($_GET['insert_category'])){
                include('insert_categories.php');
            }
            if(isset($_GET['insert_brand'])){
                include('insert_brands.php');
            }
            ?>
        </div>

        <!-- Last Child -->
        <div class="bg-info p-3 text-center footer">
            <p>Copyright@Tim Saya 2024</p>
        </div>

    </div>

</body>
</html>

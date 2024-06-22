<?php
include('../index/includes/connect.php');
session_start(); // Memulai session PHP (jika belum dimulai)


// Inisialisasi session untuk keranjang belanja jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Sesuaikan dengan struktur data keranjang Anda
}

// Proses jika tombol "Add to Cart" ditekan
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $session_id = session_id();

    // Cek apakah produk sudah ada di keranjang
    $select_query = "SELECT * FROM carts WHERE session_id = '$session_id' AND product_id = $product_id";
    $result = mysqli_query($con, $select_query);

    if (mysqli_num_rows($result) > 0) {
        // Jika produk sudah ada, update jumlahnya
        $update_query = "UPDATE carts SET quantity = quantity + 1 WHERE session_id = '$session_id' AND product_id = $product_id";
        mysqli_query($con, $update_query);
    } else {
        // Jika produk belum ada, tambahkan ke keranjang dengan jumlah 1
        $insert_query = "INSERT INTO carts (session_id, product_id, quantity) VALUES ('$session_id', $product_id, 1)";
        mysqli_query($con, $insert_query);
    }

    // Update total price in session variable
    $update_total_price_query = "UPDATE carts SET total_price = quantity * (SELECT product_price FROM products WHERE product_id = $product_id) WHERE session_id = '$session_id' AND product_id = $product_id";
    mysqli_query($con, $update_total_price_query);

    // Redirect back to index.php after adding to cart
    header("Location: index.php");
    exit;
}


// Proses update atau delete item di keranjang// Proses update atau delete item di keranjang
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
    $session_id = session_id();

    if ($action === 'update') {
        $quantity = $_POST['quantity'];
        $update_query = "UPDATE carts SET quantity = $quantity WHERE session_id = '$session_id' AND product_id = $product_id";
        mysqli_query($con, $update_query);

        // Update total price after quantity update
        $update_total_price_query = "UPDATE carts SET total_price = quantity * (SELECT product_price FROM products WHERE product_id = $product_id) WHERE session_id = '$session_id' AND product_id = $product_id";
        mysqli_query($con, $update_total_price_query);
    } elseif ($action === 'delete') {
        $delete_query = "DELETE FROM carts WHERE session_id = '$session_id' AND product_id = $product_id";
        mysqli_query($con, $delete_query);
    }

    // Redirect back to index.php after update or delete
    header("Location: index.php");
    exit;
}


// Query untuk mengambil kategori produk
$select_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($con, $select_categories);

// Variabel untuk menyimpan ID kategori yang dipilih
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;

// Query untuk mengambil produk berdasarkan kategori yang dipilih
if (!empty($selected_category)) {
    $select_query = "SELECT * FROM products WHERE category_id = $selected_category ORDER BY product_title";
} else {
    $select_query = "SELECT * FROM products ORDER BY product_title";
}
$result_query = mysqli_query($con, $select_query);

// Periksa apakah pengguna sudah login
// Periksa apakah pengguna sudah login
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['username'] : ''
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vamos Store</title>
    <!-- Bootstrap CSS Bundle, Font API Google, Font Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'>
    <link rel="stylesheet" href="./public/view/css/style.css"> 
    <script src="./public/view/js/main.js"></script>
    <style>
        /* Add your custom CSS here */
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <div class="container-fluid p-0">
        
        <!-- 1st Child - Navbar 1 -->
        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">
                <div class="logo">
                    <img src="./public/view/css/assets/logo.png" alt="#" class="logo">
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <!-- Dropdown Kategori -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?php echo $_SERVER['PHP_SELF']; ?>">All Categories</a></li>
                                <?php
                                if (mysqli_num_rows($result_categories) > 0) {
                                    while ($row_category = mysqli_fetch_assoc($result_categories)) {
                                        $category_id = $row_category['category_id'];
                                        $category_name = $row_category['category_title'];
                                        // Set URL dengan parameter kategori yang dipilih
                                        $url = $_SERVER['PHP_SELF'] . '?category=' . $category_id;
                                        echo '<li><a class="dropdown-item" href="' . $url . '">' . $category_name . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <!-- End Dropdown Kategori -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#cartModal">
                                <i class='bx bxs-cart-alt'></i>
                                <span id="cartItemCount">
                                    <?php
                                    // Hitung jumlah total item di keranjang
                                    $session_id = session_id();
                                    $count_query = "SELECT SUM(quantity) AS total_items FROM carts WHERE session_id = '$session_id'";
                                    $count_result = mysqli_query($con, $count_query);
                                    $total_items = mysqli_fetch_assoc($count_result)['total_items'];
                                    echo $total_items ?? 0; // Tampilkan jumlah item, default 0 jika tidak ada
                                    ?>
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">
    Total Price: Rp.
    <span id="totalPrice">
        <?php
        $price_query = "SELECT SUM(total_price) AS total_price FROM carts WHERE session_id = '$session_id'";
        $price_result = mysqli_query($con, $price_query);
        $total_price = mysqli_fetch_assoc($price_result)['total_price'];
        echo number_format($total_price, 2); // Format to 2 decimal places
        ?>
    </span>
</a>

                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                    <ul></ul>
                    <!-- Tombol Login/Logout -->
                    <?php if ($is_logged_in): ?>
                                <span class="nav-link">Welcome, <?php echo htmlspecialchars($user_name); ?>!</span>
                                <ul></ul>
                        <button type="button" class="btn btn-outline-danger" onclick="window.location.href='./auth/logout.php'">Logout</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='./auth/login.php'">Login</button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- 4th Child - Product Listing -->
        <div class="container mt-5">
            <div class="row">
                <!-- Looping through products -->
                <?php
                if ($result_query) {
                    while ($row = mysqli_fetch_assoc($result_query)) {
                        $product_id = $row['product_id'];
                        $product_title = $row['product_title'];
                        $product_description = $row['product_description'];
                        $product_image = $row['product_image'];
                        $product_price = $row['product_price'];
                        $category_id = $row['category_id'];
                        $brand_id = $row['brand_id'];
                        ?>
                        <div class='col-md-4 mb-2'>
                            <div class='card' style='width: 18rem;'>
                                <img src='../admin_area/product_images/<?php echo $product_image; ?>' class='card-img-top' alt='<?php echo $product_title; ?>'>
                                <div class='card-body'>
                                    <h5 class='card-title'><?php echo $product_title; ?></h5>
                                    <p class='card-text'><?php echo $product_description; ?></p>
                                    <p class='card-text'>Price: Rp.<?php echo $product_price; ?></p>
                                    <!-- Form untuk tombol Add to Cart -->
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "Produk tidak ditemukan.";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- 5th Child - Footer -->
    <footer class="text-center mt-5 p-4">
        <p>&copy; 2024 Vamos Store. All Rights Reserved.</p>
    </footer>

    <!-- Modal Cart -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Cart items list -->
                    <ul id="cartItemList" class="list-group">
                        <?php
                        $select_cart_items = "SELECT * FROM carts WHERE session_id = '$session_id'";
                        $result_cart_items = mysqli_query($con, $select_cart_items);

                        if (mysqli_num_rows($result_cart_items) === 0) {
                            echo '<li class="list-group-item">Keranjang Anda kosong.</li>';
                        } else {
                            while ($cart_item = mysqli_fetch_assoc($result_cart_items)) {
                                $product_id = $cart_item['product_id'];
                                $quantity = $cart_item['quantity'];

                                // Query untuk mendapatkan detail produk
                                $select_product = "SELECT * FROM products WHERE product_id = $product_id";
                                $result_product = mysqli_query($con, $select_product);
                                $product_data = mysqli_fetch_assoc($result_product);

                                // Tampilkan detail produk di dalam keranjang
                                echo '<li class="list-group-item">';
                                echo '<div class="row">';
                                echo '<div class="col-md-3">';
                                echo '<img src="../admin_area/product_images/' . $product_data['product_image'] . '" class="img-thumbnail" alt="' . $product_data['product_title'] . '">';
                                echo '</div>';
                                echo '<div class="col-md-7">';
                                echo '<strong>ID Produk:</strong> ' . $product_id . '<br>';
                                echo '<strong>Nama:</strong> ' . $product_data['product_title'] . '<br>';
                                echo '<strong>Deskripsi:</strong> ' . $product_data['product_description'] . '<br>';
                                echo '<strong>Harga:</strong> Rp. ' . $product_data['product_price'] . '<br>';
                                echo '<strong>Jumlah:</strong> ' . $quantity . '<br>';
                                echo '</div>';
                                echo '<div class="col-md-2">';
                                echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                                echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                                echo '<input type="hidden" name="action" value="update">';
                                echo '<div class="input-group">';
                                echo '<input type="number" class="form-control" name="quantity" value="' . $quantity . '" min="1">';
                                echo '<button type="submit" class="btn btn-outline-secondary btn-sm" name="update">Update</button>';
                                echo '</div>';
                                echo '</form>';
                                echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                                echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                                echo '<input type="hidden" name="action" value="delete">';
                                echo '<button type="submit" class="btn btn-outline-danger btn-sm mt-2" name="delete">Delete</button>';
                                echo '</form>';
                                echo '</div>';
                                echo '</div>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="handlePayment()" class="btn btn-primary">Proceed to Payment</button>


                </div>
            </div>
        </div>
    </div>
    <!-- Modal Payment -->
<!-- Modal Payment -->
<!-- Modal Payment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content for Payment Modal -->
                <p>Select your preferred payment method:</p>
                <form action="process_transaction.php" method="POST">
                    <!-- Hidden fields for session ID and total amount -->
                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                    <input type="hidden" name="total_amount" value="<?php echo $total_price; ?>">
                    <!-- Other hidden fields as needed for the transaction process -->
                    
                    <!-- Payment method selection -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="creditCard" checked>
                        <label class="form-check-label" for="creditCard">
                            Credit Card
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="bankTransfer" value="bankTransfer">
                        <label class="form-check-label" for="bankTransfer">
                            Bank Transfer
                        </label>
                    </div>
                    <!-- Add more payment methods as needed -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" onclick="handlePayment()" class="btn btn-primary">Proceed to Payment</button>

<script>
function handlePayment() {
    // Simpan informasi transaksi (sesuai dengan logika aplikasi Anda)
    // ...
    // Redirect ke halaman utama dengan status transaksi
    window.location.href = 'index.php?transaction_status=' + (transaction_success ? 'success' : 'failed');
}
</script>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Tambahkan di dalam tag <body> sebelum penutup </body> -->
    <!-- Tambahkan sebelum penutup </body> -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-rg8fuZ3XW3r2JWh40sZif52WrDUg2J/xXbmsBw0hBAPz2iJ2VzUzPqkIy7Lx2Clh" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-9aS1fFj/yZCOYjTXs0jbBjPx3ySw0k4oLNXQrF5+UyjkPAaY6z3J9tTwAy2a+aMU" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Tutup koneksi ke database setelah selesai
mysqli_close($con);
?>
<script>
function handlePayment() {
    // Lakukan logika pembayaran sesuai kebutuhan Anda di sini
    // Misalnya, kirimkan data transaksi ke server untuk diproses

    // Simulasi sukses pembayaran (ganti dengan logika sebenarnya)
    var transaction_success = true;

    // Tampilkan notifikasi pop-up berdasarkan hasil pembayaran
    if (transaction_success) {
        alert('Payment successful! Thank you for your purchase.');
        // Redirect ke halaman utama dengan status transaksi
        window.location.href = 'index.php?transaction_status=success';
    } else {
        alert('Payment failed. Please try again.');
        // Jika pembayaran gagal, Anda bisa mengarahkan pengguna ke halaman lain atau memberi tahu mereka untuk mencoba lagi
    }
}
</script>

<?php
session_start();

// Include file koneksi database
include('../includes/connect.php');

$errors = array();

// Proses form login jika dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Query untuk mencari pengguna berdasarkan username
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Jika pengguna ditemukan
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password cocok, inisialisasi sesi
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect ke halaman admin jika rolenya admin, jika tidak ke halaman utama
                if ($user['role'] == 'admin') {
                    header("Location: ../admin_area/index_admin.php");
                    exit();
                } else {
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                // Password tidak cocok
                $errors[] = "Password yang Anda masukkan salah.";
            }
        } else {
            // Username tidak ditemukan
            $errors[] = "Username tidak ditemukan.";
        }
    } else {
        // Kesalahan eksekusi query
        $errors[] = "Terjadi kesalahan: " . mysqli_error($con);
    }
}

// Tutup koneksi ke database
if (isset($con)) {
    mysqli_close($con);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VAMOUS LOGIN</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <style>
        /* Add your custom CSS here */
        .container {
            max-width: 400px;
            margin-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container {
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">VAMOUS LOGIN</h2>
            <!-- Tampilkan pesan kesalahan jika ada -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- Form login -->
            <form method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>

        <!-- Tambahkan tombol atau tautan untuk registrasi -->
        <div class="mt-3 text-center">
            Belum punya akun? <a href="register.php" class="btn btn-link">Register disini</a>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-secondary">Back to Home Page</a>
    </div>

    <!-- Bootstrap Bundle JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>

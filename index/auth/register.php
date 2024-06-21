<?php
// Mulai sesi jika belum dimulai
session_start();

// Jika pengguna sudah login, redirect ke halaman lain atau tampilkan pesan bahwa sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include file koneksi database (sesuaikan dengan struktur dan lokasi file connect.php)
include('../includes/connect.php');

// Inisialisasi variabel error
$errors = array();

// Proses form register jika dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai yang di-posting dari form
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Validasi form
    if (empty($username)) {
        $errors[] = "Username harus diisi.";
    }

    if (empty($email)) {
        $errors[] = "Email harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if (empty($password)) {
        $errors[] = "Password harus diisi.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal terdiri dari 6 karakter.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak sesuai.";
    }

    // Jika tidak ada error, proses registrasi
    if (empty($errors)) {
        // Hash password sebelum disimpan ke database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke database
        $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Registrasi berhasil, arahkan pengguna ke halaman login
            header("Location: login.php");
            exit();
        } else {
            // Jika gagal menyimpan ke database
            $errors[] = "Terjadi kesalahan saat melakukan registrasi: " . mysqli_error($con);
        }
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
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <style>
        /* Custom styles */
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin-top: 80px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            height: 45px;
        }
        .btn-primary {
            width: 100%;
            height: 45px;
            font-size: 16px;
        }
        .mt-3 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <!-- Display error messages if there are any -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <!-- Registration form -->
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="mt-3">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>


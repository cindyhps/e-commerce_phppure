<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include file koneksi database (sesuaikan dengan struktur dan lokasi file connect.php)
include('../includes/connect.php');

// Inisialisasi variabel
$user_id = '';
$username = '';
$email = '';
$role = '';
$action = 'add'; // Tambah pengguna secara default

// Proses form jika ada yang dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cek action apa yang diminta (add, edit, delete)
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
    }

    // Jika melakukan penambahan pengguna baru
    if ($action == 'add') {
        // Ambil nilai yang di-posting dari form
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $role = mysqli_real_escape_string($con, $_POST['role']);

        // Validasi form
        $errors = array();
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
            $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$hashed_password', '$email', '$role')";
            $result = mysqli_query($con, $query);

            if ($result) {
                // Registrasi berhasil
                $success_message = "Registrasi pengguna berhasil.";
                $username = '';
                $email = '';
                $role = '';
            } else {
                // Jika gagal menyimpan ke database
                $errors[] = "Terjadi kesalahan saat melakukan registrasi: " . mysqli_error($con);
            }
        }
    }

    // Jika melakukan edit pengguna
    elseif ($action == 'edit') {
        $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $role = mysqli_real_escape_string($con, $_POST['role']);

        // Update data pengguna ke database
        $query = "UPDATE users SET username='$username', email='$email', role='$role' WHERE user_id='$user_id'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $success_message = "Data pengguna berhasil diperbarui.";
            $user_id = '';
            $username = '';
            $email = '';
            $role = '';
            $action = 'add'; // Kembali ke mode tambah setelah berhasil update
        } else {
            $errors[] = "Gagal memperbarui data pengguna: " . mysqli_error($con);
        }
    }

    // Jika melakukan penghapusan pengguna
    elseif ($action == 'delete') {
        $user_id = mysqli_real_escape_string($con, $_POST['user_id']);

        // Hapus pengguna dari database
        $query = "DELETE FROM users WHERE user_id='$user_id'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $success_message = "Pengguna berhasil dihapus.";
        } else {
            $errors[] = "Gagal menghapus pengguna: " . mysqli_error($con);
        }
    }
}

// Query untuk mengambil semua data pengguna kecuali admin
$query = "SELECT user_id, username, email, role FROM users WHERE role != 'admin'";
$result = mysqli_query($con, $query);

// Inisialisasi array untuk menyimpan hasil pengambilan data
$users = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Tutup koneksi ke database
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <style>
        /* Custom styles */
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <!-- Form untuk menambah atau mengedit pengguna -->
        <form method="post">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="action" value="<?php echo $action; ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <?php if ($action == 'add'): ?>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <?php if ($action == 'add'): ?>
                <button type="submit" class="btn btn-primary">Add User</button>
            <?php elseif ($action == 'edit'): ?>
                <button type="submit" class="btn btn-primary">Update User</button>
            <?php endif; ?>
        </form>

        <!-- Daftar pengguna -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
                    <td>
                    
                        <form method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mt-4" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success mt-4" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
        <a href="index_admin.php" class="btn btn-secondary">Back to Admin Dashboard</a>
    </div>
    <!-- Bootstrap Bundle JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>

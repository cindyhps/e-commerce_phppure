<?php
session_start();
require_once 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vamos</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header>
        <h1>Vamos</h1>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="/admin/dashboard.php">Dashboard</a>
                    <a href="/admin/barang.php">Barang</a>
                    <a href="/admin/pembeli.php">Pembeli</a>
                    <a href="/admin/transaksi.php">Transaksi</a>
                <?php else: ?>
                    <a href="/customer/dashboard.php">Dashboard</a>
                    <a href="/customer/transaksi.php">Transaksi</a>
                <?php endif; ?>
                <a href="/logout.php">Logout</a>
            <?php else: ?>
                <a href="/auth/login.php">Login</a>
                <a href="/auth/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
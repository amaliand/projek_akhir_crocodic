<?php
session_start();
include 'config.php'; // Pastikan ini terhubung ke file konfigurasi database Anda

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data dari tabel orders
$result = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include"header.php"; ?>
<div class="container mt-5">
    <h2>Laporan Transaksi</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>ID Pelanggan</th>
                <th>Total Harga</th>
                <th>Jumlah Pembayaran</th>
                <th>Kembalian</th>
                <th>Tanggal Pesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $order['id']; ?></td>
                    <td><?= $order['customer_id']; ?></td>
                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($order['payment_amount'], 0, ',', '.'); ?></td>
                    <td>Rp <?= number_format($order['change_amount'], 0, ',', '.'); ?></td>
                    <td><?= $order['order_date']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

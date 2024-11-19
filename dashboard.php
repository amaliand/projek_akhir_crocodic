<?php
session_start();
include 'config.php';

// Jika belum login, alihkan ke halaman login
if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Hitung total data dari setiap tabel
$total_customers = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM customers"))['total'];
$total_categories = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM categories"))['total'];
$total_products = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$total_orders = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];

// Ambil data dari masing-masing tabel
$customers = mysqli_query($conn, "SELECT * FROM customers LIMIT 5");
$categories = mysqli_query($conn, "SELECT * FROM categories LIMIT 5");
$products = mysqli_query($conn, "SELECT * FROM products LIMIT 5");
$orders = mysqli_query($conn, "SELECT o.*, c.name as customer_name FROM orders o JOIN customers c ON o.customer_id = c.id LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include"header.php"; ?>
    <div class="container mt-5">
        <h2>Selamat Datang Di Vintage Caffe</h2>
        <p>Anda telah berhasil login. Ini adalah halaman dashboard.</p>
        
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard Admin</h2>

        <!-- Panel informasi jumlah data -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <p class="card-text"><?= $total_customers; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <p class="card-text"><?= $total_categories; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text"><?= $total_products; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?= $total_orders; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel data customers -->
        <h4>Recent Customers</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = mysqli_fetch_assoc($customers)) { ?>
                    <tr>
                        <td><?= $customer['id']; ?></td>
                        <td><?= $customer['name']; ?></td>
                        <td><?= $customer['phone']; ?></td>
                        <td><?= $customer['email']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tabel data categories -->
        <h4>Recent Categories</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                    <tr>
                        <td><?= $category['id']; ?></td>
                        <td><?= $category['name']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tabel data products -->
        <h4>Recent Products</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category ID</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                    <tr>
                        <td><?= $product['id']; ?></td>
                        <td><?= $product['name']; ?></td>
                        <td><?= $product['category_id']; ?></td>
                        <td><?= $product['price']; ?></td>
                        <td><?= $product['stock']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tabel data orders -->
        <h4>Recent Orders</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
                    <tr>
                        <td><?= $order['id']; ?></td>
                        <td><?= $order['customer_name']; ?></td>
                        <td><?= $order['total_price']; ?></td>
                        <td><?= $order['order_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
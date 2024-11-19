<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Selamat Datang Di Vintage Caffe</h1>
    <h3>Admin: <?= $_SESSION['admin_email']; ?></h3>
    <nav class="nav nav-pills flex-column flex-sm-row mb-4">
        <a class="flex-sm-fill text-sm-center nav-link" href="dashboard.php">DASBOARD</a>
        <a class="flex-sm-fill text-sm-center nav-link active" href="customers.php">Customers</a>
        <a class="flex-sm-fill text-sm-center nav-link" href="categories.php">Categories</a>
        <a class="flex-sm-fill text-sm-center nav-link" href="products.php">Products</a>
        <a class="flex-sm-fill text-sm-center nav-link" href="orders.php">Orders</a>
        <a class="flex-sm-fill text-sm-center nav-link" href="order_products.php">Order Products</a>
       
    </nav>
</body>
</html>
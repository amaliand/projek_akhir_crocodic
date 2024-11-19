<?php
session_start();
include 'config.php';

// Create
if (isset($_POST['add_order_product'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "INSERT INTO order_products (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$quantity')");
    header("Location: order_products.php");
}

// Read
$order_products = mysqli_query($conn, "SELECT order_products.*, orders.id AS order_id, products.name AS product_name FROM order_products JOIN orders ON order_products.order_id = orders.id JOIN products ON order_products.product_id = products.id");

// Update
if (isset($_POST['update_order_product'])) {
    $id = $_POST['id'];
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "UPDATE order_products SET order_id='$order_id', product_id='$product_id', quantity='$quantity' WHERE id='$id'");
    header("Location: order_products.php");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM order_products WHERE id='$id'");
    header("Location: order_products.php");
}

// Get orders and products for select
$orders = mysqli_query($conn, "SELECT * FROM orders");
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Products CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include"headmaster.php"; ?>
<div class="container mt-5">
    <h2>Manage Order Products</h2>
    <form method="POST" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <label>Order</label>
            <select name="order_id" class="form-control" required>
                <option value="">Select Order</option>
                <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
                    <option value="<?= $order['id']; ?>"><?= $order['id']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Product</label>
            <select name="product_id" class="form-control" required>
                <option value="">Select Product</option>
                <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                    <option value="<?= $product['id']; ?>"><?= $product['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <button type="submit" name="add_order_product" class="btn btn-primary">Add Order Product</button>
    </form>

    <h4>Order Product List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order_product = mysqli_fetch_assoc($order_products)) { ?>
                <tr>
                    <td><?= $order_product['id']; ?></td>
                    <td><?= $order_product['order_id']; ?></td>
                    <td><?= $order_product['product_name']; ?></td>
                    <td><?= $order_product['quantity']; ?></td>
                    <td>
                        <a href="order_products.php?delete=<?= $order_product['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal<?= $order_product['id']; ?>">Edit</button>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal<?= $order_product['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Order Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $order_product['id']; ?>">
                                    <div class="form-group">
                                        <label>Order</label>
                                        <select name="order_id" class="form-control" required>
                                            <option value="">Select Order</option>
                                            <?php
                                            // Reset orders for update
                                            mysqli_data_seek($orders, 0);
                                            while ($order = mysqli_fetch_assoc($orders)) { ?>
                                                <option value="<?= $order['id']; ?>" <?= $order_product['order_id'] == $order['id'] ? 'selected' : ''; ?>><?= $order['id']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Product</label>
                                        <select name="product_id" class="form-control" required>
                                            <option value="">Select Product</option>
                                            <?php
                                            // Reset products for update
                                            mysqli_data_seek($products, 0);
                                            while ($product = mysqli_fetch_assoc($products)) { ?>
                                                <option value="<?= $product['id']; ?>" <?= $order_product['product_id'] == $product['id'] ? 'selected' : ''; ?>><?= $product['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" class="form-control" value="<?= $order_product['quantity']; ?>" required>
                                    </div>
                                    <button type="submit" name="update_order_product" class="btn btn-primary">Update Order Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

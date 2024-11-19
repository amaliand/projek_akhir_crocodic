<?php
session_start();
include 'config.php';

// Create
if (isset($_POST['add_order'])) {
    $customer_id = $_POST['customer_id'];
    $total_price = $_POST['total_price'];
    $payment_amount = $_POST['payment_amount'];
    $change_amount = $_POST['change_amount'];

    mysqli_query($conn, "INSERT INTO orders (customer_id, total_price, payment_amount, change_amount) VALUES ('$customer_id', '$total_price', '$payment_amount', '$change_amount')");
    header("Location: orders.php");
}

// Read
$orders = mysqli_query($conn, "SELECT orders.*, customers.name AS customer_name FROM orders JOIN customers ON orders.customer_id = customers.id");

// Update
if (isset($_POST['update_order'])) {
    $id = $_POST['id'];
    $customer_id = $_POST['customer_id'];
    $total_price = $_POST['total_price'];
    $payment_amount = $_POST['payment_amount'];
    $change_amount = $_POST['change_amount'];

    mysqli_query($conn, "UPDATE orders SET customer_id='$customer_id', total_price='$total_price', payment_amount='$payment_amount', change_amount='$change_amount' WHERE id='$id'");
    header("Location: orders.php");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id='$id'");
    header("Location: orders.php");
}

// Get customers for select
$customers = mysqli_query($conn, "SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include"headmaster.php"; ?>
<div class="container mt-5">
    <h2>Manage Orders</h2>
    <form method="POST" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php while ($customer = mysqli_fetch_assoc($customers)) { ?>
                    <option value="<?= $customer['id']; ?>"><?= $customer['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>Total Price</label>
            <input type="number" name="total_price" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Payment Amount</label>
            <input type="number" name="payment_amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Change Amount</label>
            <input type="number" name="change_amount" class="form-control" required>
        </div>
        <button type="submit" name="add_order" class="btn btn-primary">Add Order</button>
    </form>

    <h4>Order List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Payment Amount</th>
                <th>Change Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
                <tr>
                    <td><?= $order['id']; ?></td>
                    <td><?= $order['customer_name']; ?></td>
                    <td><?= $order['total_price']; ?></td>
                    <td><?= $order['payment_amount']; ?></td>
                    <td><?= $order['change_amount']; ?></td>
                    <td>
                        <a href="orders.php?delete=<?= $order['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal<?= $order['id']; ?>">Edit</button>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal<?= $order['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Order</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $order['id']; ?>">
                                    <div class="form-group">
                                        <label>Customer</label>
                                        <select name="customer_id" class="form-control" required>
                                            <option value="">Select Customer</option>
                                            <?php
                                            // Reset customers for update
                                            mysqli_data_seek($customers, 0);
                                            while ($customer = mysqli_fetch_assoc($customers)) { ?>
                                                <option value="<?= $customer['id']; ?>" <?= $order['customer_id'] == $customer['id'] ? 'selected' : ''; ?>><?= $customer['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Total Price</label>
                                        <input type="number" name="total_price" class="form-control" value="<?= $order['total_price']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Payment Amount</label>
                                        <input type="number" name="payment_amount" class="form-control" value="<?= $order['payment_amount']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Change Amount</label>
                                        <input type="number" name="change_amount" class="form-control" value="<?= $order['change_amount']; ?>" required>
                                    </div>
                                    <button type="submit" name="update_order" class="btn btn-primary">Update Order</button>
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

<?php
session_start();
include 'config.php';

// Create
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    mysqli_query($conn, "INSERT INTO customers (name, email) VALUES ('$name', '$email')");
    header("Location: customers.php");
}

// Read
$customers = mysqli_query($conn, "SELECT * FROM customers");

// Update
if (isset($_POST['update_customer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    mysqli_query($conn, "UPDATE customers SET name='$name', email='$email' WHERE id='$id'");
    header("Location: customers.php");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM customers WHERE id='$id'");
    header("Location: customers.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include"headmaster.php"; ?>
<div class="container mt-5">
    <h2>Manage Customers</h2>
    <form method="POST" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
    </form>

    <h4>Customer List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($customer = mysqli_fetch_assoc($customers)) { ?>
                <tr>
                    <td><?= $customer['id']; ?></td>
                    <td><?= $customer['name']; ?></td>
                    <td><?= $customer['email']; ?></td>
                    <td>
                        <a href="customers.php?delete=<?= $customer['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal<?= $customer['id']; ?>">Edit</button>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal<?= $customer['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Customer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $customer['id']; ?>">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $customer['name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= $customer['email']; ?>" required>
                                    </div>
                                    <button type="submit" name="update_customer" class="btn btn-primary">Update Customer</button>
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

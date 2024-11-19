<?php
session_start();
include 'config.php';

// Create
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];

    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    header("Location: categories.php");
}

// Read
$categories = mysqli_query($conn, "SELECT * FROM categories");

// Update
if (isset($_POST['update_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];

    mysqli_query($conn, "UPDATE categories SET name='$name' WHERE id='$id'");
    header("Location: categories.php");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
    header("Location: categories.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include"headmaster.php"; ?>
<div class="container mt-5">
    <h2>Manage Categories</h2>
    <form method="POST" class="mb-4">
        <input type="hidden" name="id" value="">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
    </form>
    
    <h4>Category List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                <tr>
                    <td><?= $category['id']; ?></td>
                    <td><?= $category['name']; ?></td>
                    <td>
                        <a href="categories.php?delete=<?= $category['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateModal<?= $category['id']; ?>">Edit</button>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateModal<?= $category['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $category['id']; ?>">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $category['name']; ?>" required>
                                    </div>
                                    <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
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

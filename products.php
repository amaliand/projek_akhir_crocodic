<?php
session_start();
include 'config.php'; // Pastikan ini terhubung ke file konfigurasi database Anda

// Cek apakah user sudah login
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit;
}

// Proses untuk menambahkan produk
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Upload gambar
    $target_dir = "images/"; // Ganti folder tempat menyimpan gambar menjadi "images"
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah gambar adalah file gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Cek ukuran file
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        exit;
    }

    // Cek format gambar
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Upload gambar
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Simpan informasi produk ke database
        $query = "INSERT INTO products (name, price, stock, image) VALUES ('$name', '$price', '$stock', '$target_file')";
        if (mysqli_query($conn, $query)) {
            echo "New product added successfully.";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Proses untuk menghapus produk
if (isset($_POST['delete_product'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM products WHERE id='$id'";
    mysqli_query($conn, $query);
}

// Proses untuk mengupdate produk
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Tentukan direktori untuk upload gambar
    $target_dir = "images/";

    // Jika gambar baru diupload
    if ($_FILES["image"]["name"]) {
        // Upload gambar
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $query = "UPDATE products SET name='$name', price='$price', stock='$stock', image='$target_file' WHERE id='$id'";
    } else {
        // Update tanpa mengganti gambar
        $query = "UPDATE products SET name='$name', price='$price', stock='$stock' WHERE id='$id'";
    }
    mysqli_query($conn, $query);
}

// Ambil data produk
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include"headmaster.php"; ?>
<div class="container mt-5">
    <h2>Products</h2>

    <!-- Form untuk menambahkan produk -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
    </form>

    <h3 class="mt-5">Product List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $product['id']; ?></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $product['price']; ?></td>
                    <td><?= $product['stock']; ?></td>
                    <td><img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>" width="100"></td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#updateModal<?= $product['id']; ?>">Edit</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $product['id']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal untuk update produk -->
                <div class="modal fade" id="updateModal<?= $product['id']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?= $product['id']; ?>">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control" value="<?= $product['price']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Stock</label>
                                        <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                    <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
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

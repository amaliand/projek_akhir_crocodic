<?php
session_start();
include 'config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_email'])) {
    header('Location: login.php');
    exit();
}

// Mengambil data dari database
$customers = mysqli_query($conn, "SELECT * FROM customers");
$categories = mysqli_query($conn, "SELECT * FROM categories");
$products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0"); // Hanya menampilkan produk dengan stok lebih dari 0

// Proses transaksi
if (isset($_POST['submit_transaction'])) {
    $customer_id = $_POST['customer_id'];
    $total_price = $_POST['total_price'];
    $payment_amount = $_POST['payment_amount']; // Nominal pembayaran
    $change = $payment_amount - $total_price; // Uang kembalian

    if ($change < 0) {
        echo "<script>alert('Jumlah pembayaran tidak mencukupi.'); window.location='transaksi.php';</script>";
        exit();
    }

    // Simpan order di tabel orders
    $insert_order = mysqli_query($conn, "INSERT INTO orders (customer_id, total_price, payment_amount, change_amount, order_date) VALUES ('$customer_id', '$total_price', '$payment_amount', '$change', NOW())");
    $order_id = mysqli_insert_id($conn); // Mendapatkan order_id dari transaksi

    foreach ($_POST['product_id'] as $index => $product_id) {
        $quantity = $_POST['quantity'][$index];
        $price = $_POST['price'][$index];
        $total_item_price = $quantity * $price;

        // Ambil stok produk
        $product_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stock FROM products WHERE id='$product_id'"));
        $stock = $product_data['stock'];

        // Cek apakah stok mencukupi
        if ($quantity > $stock) {
            echo "<script>alert('Stok produk tidak mencukupi. Transaksi dibatalkan.'); window.location='transaksi.php';</script>";
            exit();
        }

        // Simpan produk ke dalam tabel order_products
        $insert_order_product = mysqli_query($conn, "INSERT INTO order_products (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$total_item_price')");

        // Update stok produk
        $new_stock = $stock - $quantity;
        $update_stock = mysqli_query($conn, "UPDATE products SET stock='$new_stock' WHERE id='$product_id'");

        // Tambahkan total harga item ke total harga transaksi
        $total_price += $total_item_price;
    }

    echo "<script>alert('Transaksi berhasil! Kembalian: Rp " . number_format($change, 0, ',', '.') . "'); window.location='transaksi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .product-card {
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .cart-list {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<?php include "header.php"; ?>
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Daftar Produk -->
            <div class="col-md-8">
                <h4>Daftar Produk</h4>
                <div class="row">
                    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                        <div class="col-md-3 mb-3">
                            <div class="card product-card" onclick="addToCart(<?= $product['id']; ?>, '<?= $product['name']; ?>', <?= $product['price']; ?>, <?= $product['stock']; ?>)">
                                <img src="<?= $product['image']; ?>" class="card-img-top" alt="<?= $product['name']; ?>" onerror="this.onerror=null; this.src='images/default.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product['name']; ?></h5>
                                    <p class="card-text">Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>
                                    <p class="card-text">Stok: <?= $product['stock']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Keranjang dan Checkout -->
            <div class="col-md-4">
                <h4>Keranjang</h4>
                <form method="POST" action="transaksi.php">
                    <div class="form-group">
                        <label for="customer_id">Pilih Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">-- Pilih Customer --</option>
                            <?php while ($customer = mysqli_fetch_assoc($customers)) { ?>
                                <option value="<?= $customer['id']; ?>"><?= $customer['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Daftar Produk di Keranjang -->
                    <div class="cart-list" id="cart-list">
                        <!-- Produk yang ditambahkan akan muncul di sini -->
                    </div>

                    <div class="form-group">
                        <label>Subtotal: Rp <span id="subtotal">0</span></label>
                        <input type="hidden" name="total_price" id="total_price" value="0">
                    </div>

                    <!-- Input Pembayaran -->
                    <div class="form-group">
                        <label for="payment_amount">Jumlah Pembayaran</label>
                        <input type="number" name="payment_amount" id="payment_amount" class="form-control" required>
                    </div>

                    <button type="submit" name="submit_transaction" class="btn btn-success btn-block">Submit Transaksi</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let subtotal = 0;

        // Fungsi untuk menambahkan produk ke keranjang
        function addToCart(id, name, price, stock) {
            let quantity = prompt(`Masukkan jumlah untuk ${name} (Stok: ${stock}):`);
            quantity = parseInt(quantity);

            if (quantity > stock || quantity <= 0) {
                alert('Jumlah tidak valid atau stok tidak mencukupi.');
                return;
            }

            // Cek apakah produk sudah ada di keranjang
            const existingProduct = cart.find(item => item.id === id);

            if (existingProduct) {
                existingProduct.quantity += quantity;
            } else {
                cart.push({ id, name, price, quantity, stock });
            }

            // Update subtotal
            subtotal += quantity * price;
            document.getElementById('subtotal').textContent = subtotal;
            document.getElementById('total_price').value = subtotal;

            // Render ulang keranjang
            renderCart();
        }

        // Fungsi untuk merender keranjang
        function renderCart() {
            const cartList = document.getElementById('cart-list');
            cartList.innerHTML = '';

            cart.forEach((item, index) => {
                cartList.innerHTML += `
                    <div class="cart-item">
                        <span>${item.name} (x${item.quantity})</span>
                        <span>Rp ${item.quantity * item.price}</span>
                        <input type="hidden" name="product_id[]" value="${item.id}">
                        <input type="hidden" name="quantity[]" value="${item.quantity}">
                        <input type="hidden" name="price[]" value="${item.price}">
                    </div>
                `;
            });
        }
    </script>
</body>
</html>

<?php
session_start();
include 'config.php';

// Proses login ketika form disubmit
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));

    // Query untuk mencari user dengan email dan password yang cocok
    $query = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Simpan email admin ke sesi
        $_SESSION['admin_email'] = $email;
        header('Location: dashboard.php'); // Alihkan ke dashboard jika login berhasil
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #009688; /* Latar belakang biru tosca */
        }
        .btn-custom {
            background-color: #007bff; /* Warna biru laut */
            color: white; /* Warna teks putih */
        }
        .btn-custom:hover {
            background-color: #0056b3; /* Warna biru laut lebih gelap saat hover */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-white">Login Admin</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <?= $error; ?>
                    </div>
                <?php } ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="text-white">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="text-white">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-custom btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php

    session_start();

    if (isset($_SESSION['login'])) {
        header("Location: index1.php");
        exit;
    }

    // Cek apakah form login telah disubmit
    if (isset($_POST['login'])) {
        $nama = $_POST['nama'];
        $password = $_POST['password'];

        // Koneksi ke database
        $conn = mysqli_connect("localhost", "root", "", "antri");

        // Validasi login
        $query = "SELECT * FROM Resepsionis WHERE Nama_R = '$nama' AND ID_Resepsionis = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {

            $_SESSION['login'] = true;

            // Redirect ke halaman utama
            header("Location: index1.php");
            exit;

        } else {
            // Jika login tidak valid, tampilkan pesan error
            $error = "Nama pengguna atau kata sandi salah!";
        }

    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
</head>
<body>
    <h2>Login</h2>

    <!-- Form login -->
    <form action="" method="post">
        <label for="nama">Username : </label>
        <input type="text" id="nama" name="nama" required>
        <br>

        <label for="password">Password : </label>
        <input type="password" id="password" name="password" required>
        <br>

        <button type="submit" name="login">Login</button>
    </form>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
</body>
</html>

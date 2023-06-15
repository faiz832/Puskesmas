<?php

    session_start();

    if ( !isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "", "antri");

    if (isset($_GET['id'])) {
        $idResepsionis = $_GET['id'];

        // Mengambil data pasien berdasarkan ID_Pasien
        $query = "SELECT * FROM Resepsionis WHERE ID_Resepsionis = $idResepsionis";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $nama_r = $row['Nama_R'];
    }

    // Memperbarui data pasien
    if (isset($_POST['submit'])) {
        $namaBaru = $_POST['nama_r'];

        $queryUbah = "UPDATE Resepsionis SET Nama_R = '$namaBaru' WHERE ID_Resepsionis = $idResepsionis";

        var_dump($queryUbah);

        mysqli_query($conn, $queryUbah);

        if(mysqli_query($conn, $queryUbah)) {
            echo "
                <script>
                    alert('Data resepsionis berhasil diperbarui');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";

        } else {
            echo "
                <script>
                    alert('Data resepsionis gagal diperbarui');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";
        }

    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Data Resepsionis</title>
</head>
<body>

    <h2>Ubah Data Resepsionis</h2>

    <!-- Form edit data pasien -->
    <form action="" method="post">


        <label for="nama_r">Nama:</label>
        <input type="text" name="nama_r" value="<?= $nama_r; ?>" required><br>

        <button type="submit" name="submit">Ubah data</button>
    </form>
</body>
</html>

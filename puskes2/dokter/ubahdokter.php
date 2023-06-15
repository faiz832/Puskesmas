<?php

    session_start();

    if ( !isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    } 

    $conn = mysqli_connect("localhost", "root", "", "antri");

    if (isset($_GET['id'])) {
        $idDokter = $_GET['id'];

        // Mengambil data pasien berdasarkan ID_Pasien
        $query = "SELECT * FROM Dokter WHERE ID_Dokter = $idDokter";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $nama_p = $row['Nama_D'];
        $spesialis = $row['Spesialis'];
        $jadwal = $row['Jadwal'];
    }

    // Memperbarui data pasien
    if (isset($_POST['submit'])) {
        $namaBaru = $_POST['nama_p'];
        $spesialisBaru = $_POST['spesialis'];
        $jadwalBaru = $_POST['jadwal'];

        $queryUbah = "UPDATE Dokter SET Nama_D = '$namaBaru', Spesialis = '$spesialisBaru', Jadwal = '$jadwalBaru' WHERE ID_Dokter = $idDokter";

        var_dump($queryUbah);

        mysqli_query($conn, $queryUbah);

        if(mysqli_query($conn, $queryUbah)) {
            echo "
                <script>
                    alert('Data dokter berhasil diperbarui');
                    document.location.href = 'tampildokter.php';
                </script>
            ";

        } else {
            echo "
                <script>
                    alert('Data dokter gagal diperbarui');
                    document.location.href = 'tampildokter.php';
                </script>
            ";
        }

    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Data Dokter</title>
</head>
<body>
    

    <h2>Ubah Data Dokter</h2>

    <!-- Form edit data pasien -->
    <form action="" method="post">
        <label for="nama_p">Nama:</label>
        <input type="text" name="nama_p" value="<?= $nama_p; ?>" required><br>

        <label for="spesialis">Spesialis:</label>
        <input type="text" name="spesialis" value="<?= $spesialis; ?>" required><br>

        <label for="jadwal">Jadwal</label>
        <input type="text" name="jadwal" value="<?= $jadwal; ?>" required><br>

        <button type="submit" name="submit">Ubah data</button>
    </form>
</body>
</html>

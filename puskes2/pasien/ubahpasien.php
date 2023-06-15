<?php

    session_start();

    if ( !isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    } 

    $conn = mysqli_connect("localhost", "root", "", "antri");

    if (isset($_GET['id'])) {
        $idPasien = $_GET['id'];

        // Mengambil data pasien berdasarkan ID_Pasien
        $query = "SELECT * FROM Pasien WHERE ID_Pasien = $idPasien";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $nama_p = $row['Nama_P'];
        $alamat = $row['Alamat'];
        $tglLahir = $row['Tgl_lahir'];
        $noHP = $row['No_HP'];
        $jenisKelamin = $row['Jenis_Kelamin'];
    }

    // Memperbarui data pasien
    if (isset($_POST['submit'])) {
        $namaBaru = $_POST['nama_p'];
        $alamatBaru = $_POST['alamat'];
        $tglLahirBaru = $_POST['tgl_lahir'];
        $noHPBaru = $_POST['no_hp'];
        $jenisKelaminBaru = $_POST['jenis_kelamin'];

        $queryUbah = "UPDATE Pasien SET Nama_P = '$namaBaru', Alamat = '$alamatBaru', Tgl_lahir = '$tglLahirBaru', No_HP = '$noHPBaru', Jenis_Kelamin = '$jenisKelaminBaru' WHERE ID_Pasien = $idPasien";

        mysqli_query($conn, $queryUbah);

        if(mysqli_query($conn, $queryUbah)) {
            echo "
                <script>
                    alert('Data pasien berhasil diperbarui');
                    document.location.href = 'tampilpasien.php';
                </script>
            ";

        } else {
            echo "
                <script>
                    alert('Data pasien gagal diperbarui');
                    document.location.href = 'tampilpasien.php';
                </script>
            ";
        }

    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Data Pasien</title>
</head>
<body>
    

    <h2>Ubah Data Pasien</h2>

    <!-- Form edit data pasien -->
    <form action="" method="post">
        <label for="nama_p">Nama:</label>
        <input type="text" name="nama_p" value="<?php echo $nama_p; ?>" required><br>

        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" value="<?php echo $alamat; ?>" required><br>

        <label for="tgl_lahir">Tanggal Lahir:</label>
        <input type="date" name="tgl_lahir" value="<?php echo $tglLahir; ?>" required><br>

        <label for="no_hp">No. HP:</label>
        <input type="text" name="no_hp" value="<?php echo $noHP; ?>" required><br>

        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select name="jenis_kelamin" required>
            <option value="L" <?php if ($jenisKelamin == 'L') echo 'selected'; ?>>Laki-laki</option>
            <option value="P" <?php if ($jenisKelamin == 'P') echo 'selected'; ?>>Perempuan</option>
        </select><br>
        <button type="submit" name="submit">Ubah data</button>
    </form>
</body>
</html>

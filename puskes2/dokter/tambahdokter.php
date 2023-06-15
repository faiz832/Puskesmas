<?php

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	if(isset($_POST['submit'])){
        $idDokter = $_GET['id'];
		$nama_d = htmlspecialchars($_POST['nama_d']);
		$spesialis = htmlspecialchars($_POST['spesialis']);
		$jadwal = htmlspecialchars($_POST['jadwal']);

		$query = "INSERT INTO Dokter (ID_Dokter, Nama_D, Spesialis, Jadwal) VALUES ('$idDokter','$nama_d', '$spesialis', '$jadwal')";

		if (mysqli_query($conn, $query)) {
            echo "
            	<script>
            		alert('Data dokter berhasil ditambahkan');
            		document.location.href = 'tampildokter.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Data dokter gagal ditambahkan');
            		document.location.href = 'tampildokter.php';
            	</script>
            ";
        }
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Tambah Data Dokter</title>
</head>
<body>
	<h1>Tambah Data Dokter</h1>
	<form action="" method="post">
		<label for="nama_d">Nama:</label>
        <input type="text" name="nama_d" id="nama_d" required>
        <br>

        <label for="spesialis">Spesialis:</label>
        <input type="text" name="spesialis" id="spesialis" required>
        <br>

        <label for="jadwal">Jadwal:</label>
        <input type="text" name="jadwal" id="jadwal" required>
        <br>

        <button type="submit" name="submit">Simpan data</button>

	</form>
</body>
</html>
<?php

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	if(isset($_POST['submit'])){
        $idResepsionis = $_GET['id'];
		$nama_r = htmlspecialchars($_POST['nama_r']);

		$query = "INSERT INTO Resepsionis (ID_Resepsionis, Nama_R) VALUES ('$idResepsionis','$nama_r')";

		if (mysqli_query($conn, $query)) {
            echo "
            	<script>
            		alert('Data resepsionis berhasil ditambahkan');
            		document.location.href = 'tampilresepsionis.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Data resepsionis gagal ditambahkan');
            		document.location.href = 'tampilresepsionis.php';
            	</script>
            ";
        }
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Tambah Data Resepsionis</title>
</head>
<body>
	<h1>Tambah Data Resepsionis</h1>
	<form action="" method="post">
		<label for="nama_r">Nama:</label>
        <input type="text" name="nama_r" id="nama_r" required>
        <br>

        <button type="submit" name="submit">Simpan data</button>

	</form>
</body>
</html>
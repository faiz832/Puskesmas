<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	if(isset($_POST['submit'])){
		$nama_p = htmlspecialchars($_POST['nama_p']);
		$alamat = htmlspecialchars($_POST['alamat']);
		$tgl_lahir = $_POST['tgl_lahir'];
		$no_hp = htmlspecialchars($_POST['no_hp']);
		$jenis_kelamin = $_POST['jenis_kelamin'];

		$query1 = "INSERT INTO Pasien (Nama_P, Alamat, Tgl_lahir, No_HP, Jenis_Kelamin) VALUES ('$nama_p', '$alamat', '$tgl_lahir', '$no_hp', '$jenis_kelamin')";

		if (mysqli_query($conn, $query1)) {
            echo "
            	<script>
            		alert('Data pasien berhasil ditambahkan');
            		document.location.href = 'tampilpasien.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Data pasien gagal ditambahkan');
            		document.location.href = 'tampilpasien.php';
            	</script>
            ";
        }
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Tambah Data Pasien</title>
</head>
<body>
	<h1>Tambah Data Pasien</h1>
	<form action="" method="post">
		<label for="nama_p">Nama:</label>
        <input type="text" name="nama_p" id="nama_p" required>
        <br>

        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" id="alamat" required>
        <br>

        <label for="tgl_lahir">Tanggal Lahir:</label>
        <input type="date" name="tgl_lahir" id="tgl_lahir" required>
        <br>

        <label for="no_hp">No. HP:</label>
        <input type="text" name="no_hp" id="no_hp" required>
        <br>

        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select name="jenis_kelamin" id="jenis_kelamin" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <br>

        <label for="status">Status Antri:</label>
        <select name="status" id="status" required>
            <option value="Menunggu">Menunggu</option>
            <option value="Diperiksa">Diperiksa</option>
            <option value="Selesai">Selesai</option>
        </select>
        <br>

        <button type="submit" name="submit">Simpan data</button>

	</form>
</body>
</html>
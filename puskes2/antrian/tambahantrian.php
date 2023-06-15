<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	// Fitur auto complete
	$queryc = "SELECT ID_Pasien, Nama_P FROM Pasien";
	$resultc = mysqli_query($conn, $queryc);
	$queryc2 = "SELECT ID_Dokter, Nama_D, Spesialis FROM Dokter";
	$resultc2 = mysqli_query($conn, $queryc2);

	$pilihanPasien = array();
	while ($row = mysqli_fetch_assoc($resultc)) {
	    $pilihanPasien[] = $row['ID_Pasien'] . ' - ' . $row['Nama_P'];
	}

	$pilihanDokter = array();
	while ($row = mysqli_fetch_assoc($resultc2)) {
	    $pilihanDokter[] = $row['ID_Dokter'] . ' - ' . $row['Nama_D'] . ' (Spesialis: ' . $row['Spesialis'] . ')';
	}

	// Inisialisasi input teks
	$idPasien = '';
	$idDokter = '';

	// Proses ketika form dikirimkan
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    $idPasien = $_POST['idPasien'];
	    $idDokter = $_POST['idDokter'];
	}


	if(isset($_POST['submit'])){
		$idAntrian = $_GET['id'];
		$idPasien = $_POST['idPasien'];
		$idResepsionis = "1";
		$idDokter = $_POST['idDokter'];
		$tglAntrian = "2024-06-06";
		$status = "Menunggu";

		$idPasien = explode(" ", $idPasien)[0];

		$idDokter = explode(" ", $idDokter)[0];

		$query1 = "INSERT INTO Antrian (ID_Antrian, ID_Resepsionis, ID_Pasien, ID_Dokter, Tgl_Antrian, Status) VALUES ('$idAntrian', '$idResepsionis', '$idPasien', '$idDokter', '$tglAntrian', '$status')";

		if (mysqli_query($conn, $query1)) {
            echo "
            	<script>
            		alert('Antrian berhasil ditambahkan');
            		document.location.href = '../index1.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Antrian gagal ditambahkan');
            		document.location.href = '../index1.php';
            	</script>
            ";
        }
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Tambah Antrian</title>

	<!-- Fitur auto complete -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            var pilihanPasien = <?php echo json_encode($pilihanPasien); ?>;
            var pilihanDokter = <?php echo json_encode($pilihanDokter); ?>;

            // Inisialisasi autocompletion pada input teks
            $('#idPasien').autocomplete({
                source: pilihanPasien
            });

            $('#idDokter').autocomplete({
                source: pilihanDokter
            });
        });
    </script>

</head>
<body>
	<h1>Tambah Antrian</h1>
	<form action="" method="post">
		<label for="idPasien">ID Pasien:</label>
        <input type="text" name="idPasien" size="50" id="idPasien" required>
        <br>

        <label for="idDokter">ID Dokter:</label>
        <input type="text" name="idDokter" size="50" id="idDokter" required>
        <br>

        <button type="submit" name="submit">Simpan data</button>

	</form>
</body>
</html>
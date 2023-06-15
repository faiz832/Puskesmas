<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 

	$conn = mysqli_connect("localhost", "root", "", "antri");

	$ID_Antrian = $_GET['id'];

	$query = "DELETE FROM Antrian WHERE ID_Antrian = $ID_Antrian";

	mysqli_query($conn, $query);

	if(mysqli_query($conn, $query)) {
		echo "
			<script>
				alert('Antrian Selesai');
				document.location.href = '../index1.php';
			</script>
		";

	} else {
		echo "
			<script>
				alert('Antrian gagal diselesaikan');
				document.location.href = '../index1.php';
			</script>
		";
	}

?>
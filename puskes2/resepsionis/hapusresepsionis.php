<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 

	$conn = mysqli_connect("localhost", "root", "", "antri");

	$ID_Resepsionis = $_GET['id'];

	$query = "DELETE FROM Resepsionis WHERE ID_Resepsionis = $ID_Resepsionis";

	mysqli_query($conn, $query);

	if(mysqli_query($conn, $query)) {
		echo "
			<script>
				alert('Data resepsionis berhasil dihapus');
				document.location.href = 'tampilresepsionis.php';
			</script>
		";

	} else {
		echo "
			<script>
				alert('Data resepsionis gagal dihapus');
				document.location.href = 'tampilresepsionis.php';
			</script>
		";
	}

?>
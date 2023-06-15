<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

$result = mysqli_query($conn, "SELECT A.ID_Antrian, P.ID_Pasien, P.Nama_P, P.Jenis_Kelamin, P.No_HP, D.Spesialis, A.Status
                                FROM Antrian AS A
                                LEFT JOIN Pasien AS P ON A.ID_Pasien = P.ID_Pasien
                                LEFT JOIN Dokter AS D ON A.ID_Dokter = D.ID_Dokter");

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM Antrian 
              LEFT JOIN Pasien ON Antrian.ID_Pasien = Pasien.ID_Pasien
              LEFT JOIN Dokter ON Antrian.ID_Dokter = Dokter.ID_Dokter
              LEFT JOIN Resepsionis ON Antrian.ID_Resepsionis = Resepsionis.ID_Resepsionis 
              WHERE Nama_P LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Resepsionis</title>
    <style>
        .float-window {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 300px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            z-index: 9999;
        }

        .nested-float-window {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 200px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            z-index: 9999;
        }

        .nested-float-window2 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 250px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 20px;
            z-index: 9999;
        }

    </style>
</head>
<body>

    	<br><br><br>

        <a href="logout.php">Log out</a>

	    <h1>Daftar Antrian</h1>

	    <form action="" method="post">
	        <input type="text" name="keyword" size="40" autofocus placeholder="Cari...">
	        <button type="submit" name="cari">Search</button>
	    </form>
	    <br>

	    <table border="1" cellpadding="10" cellspacing="0">
	        <tr>
	            <th>No.</th>
	            <th>ID Pasien</th>
	            <th>Nama Pasien</th>
	            <th>Jenis Kelamin</th>
	            <th>No. HP</th>
                <th>Berobat</th>
	            <th>Status</th>
	            <th>Aksi</th>
	        </tr>

	        <?php $nomor = 1 ?>

	        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	            $data = $resultcari;
	        } else {
	            $data = $result;
	        } ?>

	        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
	            <tr>
	                <td><?= $nomor++; ?></td>
	                <td><?= $row["ID_Pasien"]; ?></td>
	                <td><?= $row["Nama_P"]; ?></td>
	                <td><?= $row["Jenis_Kelamin"]; ?></td>
	                <td><?= $row["No_HP"]; ?></td>
                    <td><?= $row["Spesialis"]; ?></td>
	                <td><?= $row["Status"]; ?></td>
	                <td>
	                    <button onclick="openFloatWindow(<?= $row["ID_Antrian"]; ?>,
	                    								'<?= $row["Nama_P"]; ?>',
	                    								'<?= $row["No_HP"]; ?>',
	                    								'<?= $row["Spesialis"]; ?>',
	                    								'<?= $row["Status"]; ?>'
	                    								)">Detail</button>
	                </td>
	            </tr>
                <?php $idAntrian = $row["ID_Antrian"] + 1 ?>
	        <?php endwhile; ?>
	    </table>

	    <a href="antrian/tambahantrian.php?id=<?= $idAntrian; ?>">Tambah Antrian</a>







	    <script>
	        function openFloatWindow(id, nama, hp, spesialis, status) {
	            var floatWindow = document.createElement('div');
	            floatWindow.className = 'float-window';

	            floatWindow.innerHTML = `
	                <h2>Data Pasien</h2>
	                <p>Nama : ${nama}</p>
	                <p>No HP : ${hp}</p>
	                <p>Berobat : ${spesialis}</p>
	                <p>Status : ${status}</p>
	                <button onclick="openNestedFloatWindow(${id})">Hapus Antrian</button>
	                <button onclick="openNested2FloatWindow(${id})">Ubah Status</button>
	                <button onclick="closeFloatWindow()">Tutup</button>
	            `;

	            document.body.appendChild(floatWindow);
	        }

	        function openNestedFloatWindow(id) {
	            var nestedFloatWindow = document.createElement('div');
	            nestedFloatWindow.className = 'nested-float-window';

	            nestedFloatWindow.innerHTML = `
	                <h2>Hapus data pasien</h2>
	                <p>Apakah anda yakin hapus data dengan id "${id}" ?</p>
	                <button onclick="window.location.href='antrian/hapusantrian.php?id=${id}'">Hapus</button>
	                <button onclick="closeNestedFloatWindow()">Batal</button>
	            `;

	            var floatWindow = document.querySelector('.float-window');
	            floatWindow.appendChild(nestedFloatWindow);
	        }

	        function openNested2FloatWindow(id) {
	            var nestedFloatWindow = document.createElement('div');
	            nestedFloatWindow.className = 'nested-float-window2';

	            nestedFloatWindow.innerHTML = `
	                <h2>Ubah status pasien</h2>
	                <p>Ubah status data dengan id "${id}" ?</p>
			        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=1'">Menunggu</button>
			        <br>
			        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=2'">Diperiksa</button>
			        <br>
	                <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=3'">Selesai</button>
	                <br><br>

	                <form action="antrian/ubahantrian.php" method="get">
	                	<label for="urutan">Urutan:</label>
	        			<input type="text" name="urutan" size="5" id="urutan">
	        			<input type="hidden" name="id" value="${id}">
	        			<button type="submit">OK</button>
	                </form>

        			<br><br>
	                <button onclick="closeNested2FloatWindow()">Kembali</button>
	            `;

	            var floatWindow = document.querySelector('.float-window');
	            floatWindow.appendChild(nestedFloatWindow);
	        }

	        function closeFloatWindow() {
	            var floatWindow = document.querySelector('.float-window');
	            if (floatWindow) {
	                floatWindow.parentNode.removeChild(floatWindow);
	            }
	        }

	        function closeNestedFloatWindow() {
	            var nestedFloatWindow = document.querySelector('.nested-float-window');
	            if (nestedFloatWindow) {
	                nestedFloatWindow.parentNode.removeChild(nestedFloatWindow);

	                var floatWindow = document.querySelector('.float-window');
	                floatWindow.style.display = 'block';
	            }
	        }

	        function closeNested2FloatWindow() {
	            var nestedFloatWindow = document.querySelector('.nested-float-window2');
	            if (nestedFloatWindow) {
	                nestedFloatWindow.parentNode.removeChild(nestedFloatWindow);

	                var floatWindow = document.querySelector('.float-window');
	                floatWindow.style.display = 'block';
	            }
	        }
	    </script>

</body>
</html>

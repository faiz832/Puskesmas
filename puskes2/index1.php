<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

$result = mysqli_query($conn, "SELECT A.ID_Antrian, P.ID_Pasien, P.Nama_P, P.No_HP, D.Spesialis, A.Status
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
              WHERE Nama_P LIKE ? OR Antrian.ID_Pasien LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Antrian</title>
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

        body {
            margin: 0;
            padding: 0;
        }

        #sidebar {
            position: fixed;
            left: -200px;
            width: 200px;
            height: 100%;
            background-color: #333;
            color: #fff;
            transition: left 0.3s ease-in-out;
        }

        #sidebar.active {
            left: 0;
        }

        #content {
            padding-left: 0;
            transition: padding-left 0.3s ease-in-out;
        }

        #content.active {
            padding-left: 200px;
        }

        #sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        #sidebar ul li {
            padding: 10px;
        }

        #sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        #sidebar ul li.active {
            background-color: #666;
        }

        #main-btn {
            position: absolute;
            top: 10px;
            right: -40px;
            cursor: pointer;
            padding: 10px;
            background-color: #333;
            color: #fff;
            font-size: 16px;
            border: none;
            transition: right 0.3s ease-in-out;
        }

        #main-btn.active {
            right: 10px;
        }

        #registration {
            padding: 10px;
        }

        #registration ul {
            list-style-type: none;
            padding: 0;
        }

        #registration ul li {
            margin-bottom: 5px;
        }

        #registration ul li a {
            color: #fff;
            text-decoration: none;
        }

    </style>
</head>
<body>
	<div id="sidebar">
        <ul>
            <li><a href="index1.php" class="active">Daftar Antrian</a></li>
            
            <li>
            	<a href="#" onclick="toggleDataList()">Data Lengkap</a>
                <ul class="sub-list" id="data-list">
                    <li><a href="resepsionis/tampilresepsionis.php">Data Resepsionis</a></li>
            		<li><a href="dokter/tampildokter.php">Data Dokter</a></li>
            		<li><a href="pasien/tampilpasien.php">Data Pasien</a></li>
                </ul>
            </li>
        </ul>
        <button id="main-btn"> = </button>
    </div>

    <div id="content">

    	<br><br><br>

        <a href="logout.php">Log out</a>

	    <h1>Daftar Antrian</h1>

	    <form action="" method="post">
	        <input type="text" name="keyword" size="40" autofocus placeholder="Cari id atau nama...">
	        <button type="submit" name="cari">Search</button>
	    </form>
	    <br>

	    <table border="1" cellpadding="10" cellspacing="0">
	        <tr>
	            <th>No.</th>
	            <th>ID Pasien</th>
	            <th>Nama Pasien</th>
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
	                <button onclick="openNestedFloatWindow(${id})">Ubah Antrian</button>
	                <button onclick="openNested2FloatWindow(${id})">Ubah Status</button>
	                <button onclick="closeFloatWindow()">Tutup</button>
	            `;

	            document.body.appendChild(floatWindow);
	        }

	        function openNestedFloatWindow(id) {
	            var nestedFloatWindow = document.createElement('div');
	            nestedFloatWindow.className = 'nested-float-window';

	            nestedFloatWindow.innerHTML = `
	                <h2>Ubah urutan antrian</h2>
	                <p> Antrian dengan id "${id}" akan diubah ke </p>
	                <form action="antrian/ubahantrian.php" method="get">
	                	<label for="urutan">Urutan:</label>
	        			<input type="text" name="urutan" size="5" id="urutan">
	        			<input type="hidden" name="id" value="${id}">
	        			<button type="submit">OK</button>
	                </form>

        			<br><br>
	                <button onclick="closeNestedFloatWindow()">Kembali</button>
	            `;

	            var floatWindow = document.querySelector('.float-window');
	            floatWindow.appendChild(nestedFloatWindow);
	        }

	        function openNested2FloatWindow(id) {
	            var nestedFloatWindow = document.createElement('div');
	            nestedFloatWindow.className = 'nested-float-window2';

	            nestedFloatWindow.innerHTML = `
	                <h2>Ubah status pasien</h2>
	                <p>Pilih status untuk data dengan id "${id}" </p>
			        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=1'">Menunggu</button>
			        <br>
			        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=2'">Diperiksa</button>
			        <br>
	                <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=3'">Selesai</button>
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
	</div>

    <script>
        var sidebar = document.getElementById('sidebar');
        var content = document.getElementById('content');
        var mainBtn = document.getElementById('main-btn');
        var listItems = document.querySelectorAll('#sidebar ul li a');

        mainBtn.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
            mainBtn.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                mainBtn.textContent = 'X';
            } else {
                mainBtn.textContent = ' = ';
            }
        });

        listItems.forEach(function (item) {
            item.addEventListener('click', function () {
                listItems.forEach(function (li) {
                    li.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        var dataList = document.getElementById("data-list");
        dataList.style.display = "none";

        function toggleDataList() {
            dataList.style.display = dataList.style.display === "none" ? "block" : "none";
        }


    </script>
</body>
</html>

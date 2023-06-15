<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

$result = mysqli_query($conn, "SELECT * FROM Resepsionis");

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM Resepsionis WHERE Nama_R LIKE ?";

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
    <title>Data Resepsionis</title>
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
            <li><a href="../index1.php" class="active">Daftar Antrian</a></li>
            
            <li>
            	<a href="#" onclick="toggleDataList()">Data Lengkap</a>
                <ul class="sub-list" id="data-list">
                    <li><a href="resepsionis/tampilresepsionis.php">Data Resepsionis</a></li>
            		<li><a href="../dokter/tampildokter.php">Data Dokter</a></li>
            		<li><a href="../pasien/tampilpasien.php">Data Pasien</a></li>
                </ul>
            </li>
        </ul>
        <button id="main-btn"> = </button>
    </div>

    <div id="content">

    	<br><br><br>

        <a href="logout.php">Log out</a>

	    <h1>Data Resepsionis</h1>

	    <form action="" method="post">
	        <input type="text" name="keyword" size="40" autofocus placeholder="Cari nama...">
	        <button type="submit" name="cari">Search</button>
	    </form>
	    <br>

	    <table border="1" cellpadding="10" cellspacing="0">
	        <tr>
	            <th>No.</th>
	            <th>ID Resepsionis</th>
	            <th>Nama Resepsionis</th>
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
	                <td><?= $row["ID_Resepsionis"]; ?></td>
	                <td><?= $row["Nama_R"]; ?></td>
	                <td>
	                    <button onclick="openFloatWindow(<?= $row["ID_Resepsionis"]; ?>, 
	                    								'<?= $row["Nama_R"]; ?>'
	                    								)">Detail</button>
	                </td>
	            </tr>
                <?php $idResepsionis = $row["ID_Resepsionis"] + 1 ?>
	        <?php endwhile; ?>
	    </table>

	    <a href="tambahresepsionis.php?id=<?= $idResepsionis; ?>">Tambah Resepsionis</a>







	    <script>

	    	
	        function openFloatWindow(id, nama) {
	            var floatWindow = document.createElement('div');
	            floatWindow.className = 'float-window';

	            floatWindow.innerHTML = `
	                <h2>Data Dokter</h2>
	                <p>ID : ${id}</p> 
	                <p>Nama : ${nama}</p>
	                <button onclick="openNestedFloatWindow(${id})">Hapus Data</button>
	                <button onclick="window.location.href='ubahresepsionis.php?id=${id}'">Ubah Data</button>
	                <button onclick="closeFloatWindow()">Tutup</button>
	            `;

	            document.body.appendChild(floatWindow);
	        }

	        function openNestedFloatWindow(id) {
	            var nestedFloatWindow = document.createElement('div');
	            nestedFloatWindow.className = 'nested-float-window';

	            nestedFloatWindow.innerHTML = `
	                <h2>Hapus data resepsionis</h2>
	                <p>Apakah anda yakin hapus data dengan id "${id}" ?</p>
	                <button onclick="window.location.href='hapusresepsionis.php?id=${id}'">Hapus</button>
	                <button onclick="closeNestedFloatWindow()">Batal</button>
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

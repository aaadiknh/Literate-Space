
    <?php  

require_once 'db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($stmt = $conn->prepare("DELETE FROM book_shelf WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: rak_book.php"); 
            exit();
        } else {
            die("Gagal mengeksekusi statement delete: " . $stmt->error);
        }
        
    } else {
        die("Gagal mempersiapkan statement untuk delete: " . $conn->error);
    }
}


if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $location = trim($_POST['location']);
    $name = trim($_POST['name']);

    if (!empty($location) && !empty($name)) { 
        if ($id > 0) {
            $sql = "UPDATE book_shelf SET location = ?, name = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssi", $location, $name, $id);
                if ($stmt->execute()) {
                    header("Location: rak_book.php"); 
                    exit();
                } else {
                    die("Gagal mengeksekusi statement update: " . $stmt->error);
                }
                $stmt->close();
            } else {
                die("Gagal mempersiapkan statement untuk update: " . $conn->error);
            }
        } else {
            // Tambah rak buku baru
            $sql = "INSERT INTO book_shelf (location, name) VALUES (?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $location, $name);
                if ($stmt->execute()) {
                    header("Location: rak_book.php"); 
                    exit();
                } else {
                    die("Gagal mengeksekusi statement insert: " . $stmt->error);
                }
                $stmt->close();
            } else {
                die("Gagal mempersiapkan statement untuk insert: " . $conn->error);
            }
        }
    } else {
        echo "Lokasi dan Jenis Buku tidak boleh kosong.";
    }
}

$sql = "SELECT * FROM book_shelf";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Rak Buku</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text h1 {
            margin: 0;
            font-size: 32px;
            color: #333;
        }

        .header-text p {
            margin: 5px 0 0;
            color: #777;
        }

        .add-button {
            padding: 10px 20px;
            background-color: #fc0000;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .add-button img {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #8a7fea;
            color: white;
        }

        tbody tr:nth-child(odd) {
            background-color: #ededed;
        }

        tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .button {
            padding: 8px 12px;
            background-color: #5f49f6;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .button img {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }

        .form-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .form-container form {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #CBCBFF;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .form-container form h2 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #7d6eff;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #5b4acb;
        }

        .form-group button {
            padding: 10px;
            background-color: #7d6eff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group button img {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .add-button {
                margin-top: 10px;
            }

            table, .form-container form {
                width: 100%;
                overflow-x: auto;
            }
        }
        .close-btn {
            position: absolute;
            top: 50px;
            right: 93%;
            width: 30px;
            height: 30px;
            background-color: red;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            line-height: 0;
            border: none;
        }

        .close-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-text">
            <h1>Manajemen Rak Buku LiterateSpace</h1>
        </div>
        <a href="?add=1" class="add-button">
            <img src="../assets/images/logo tambah.png" alt="Tambah Rak Buku">Tambah Rak</a>
            <button class="close-btn" onclick="exitPage()">×</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lokasi</th>
                <th>Jenis Buku</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["location"]) . "</td>
                        <td>" . htmlspecialchars($row["name"]) . "</td>
                        <td>
                            <div class='button-group'>
                                <a href='?edit=" . urlencode($row['id']) . "' class='button'>
                                <img src='../assets/images/logo edit.png' alt='Edit'>  Edit</a>
                                <a href='?delete=" . urlencode($row['id']) . "' class='button' onclick=\"return confirm('Apakah Anda yakin ingin menghapus rak ini?');\">
                                <img src='../assets/images/logo hapus.png' alt='Hapus'>Hapus</a>
                            </div>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data ditemukan</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <?php

    if (isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $stmt = $conn->prepare("SELECT * FROM book_shelf WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    ?>

    <div class="form-container">
        <form action="" method="POST">
            <h2>Edit Rak Buku</h2>
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="form-group">
                <label for="location">Lokasi</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($data['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Jenis Buku</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" name="save">
                    <img src="../assets/images/logo simpan.png" alt="Simpan">Simpan
                </button>
            </div>
        </form>
    </div>

    <?php
    } elseif (isset($_GET['add'])) {
    ?>

    <div class="form-container">
        <form action="" method="POST">
            <h2>Tambah Rak Buku</h2>
            <input type="hidden" name="id" value="0">
            <div class="form-group">
                <label for="location">Lokasi</label>
                <input type="text" name="location" required>
            </div>
            <div class="form-group">
                <label for="name">Jenis Buku</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <button type="submit" name="save">
                    <img src="../assets/images/logo tambah.png" alt="Tambah">Tambah
                </button>
            </div>
            <button type="submit" name="save">
        </form>
    </div>

    <?php } ?>

</div>
<script>
        function exitPage() {
            window.location.href = 'dashboard_admin.php';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>

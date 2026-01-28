<?php

require_once 'db.php';

$id = 0;
$title = "";
$author = "";
$published_year = "";
$shelf_id = "";
$action = "Tambah Buku";
$error_message = "";
$success_message = "";


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($stmt = $conn->prepare("DELETE FROM books WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: daftar_book.php");
            exit();
        } else {
            $error_message = "Gagal mengeksekusi statement delete: " . $stmt->error;
        }
    } else {
        $error_message = "Gagal mempersiapkan statement untuk delete: " . $conn->error;
    }
}


if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $published_year = intval($_POST['published_year']);
    $shelf_id = intval($_POST['shelf_id']);

    if (!empty($title) && !empty($author) && $published_year > 0 && $shelf_id > 0) {

        $checkShelf = $conn->prepare("SELECT id FROM book_shelf WHERE id = ?");
        $checkShelf->bind_param("i", $shelf_id);
        $checkShelf->execute();
        $checkShelfResult = $checkShelf->get_result();

        if ($checkShelfResult->num_rows === 0) {
            $error_message = "Rak buku tidak valid.";
        } else {
            $checkShelf->close();

            if ($id > 0) {
                // Update buku
                $sql = "UPDATE books SET title = ?, author = ?, published_year = ?, shelf_id = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssiii", $title, $author, $published_year, $shelf_id, $id);
                    if ($stmt->execute()) {
                        $stmt->close();
                        header("Location: daftar_book.php");
                        exit();
                    } else {
                        $error_message = "Gagal mengeksekusi statement update: " . $stmt->error;
                    }
                } else {
                    $error_message = "Gagal mempersiapkan statement untuk update: " . $conn->error;
                }
            } else {

                // Tambah buku baru
                $sql = "INSERT INTO books (title, author, published_year, shelf_id) VALUES (?, ?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssii", $title, $author, $published_year, $shelf_id);
                    if ($stmt->execute()) {
                        $stmt->close();
                        header("Location: daftar_book.php");
                    } else {
                        $error_message = "Gagal mengeksekusi statement insert: " . $stmt->error;
                    }
                } else {
                    $error_message = "Gagal mempersiapkan statement untuk insert: " . $conn->error;
                }
            }
        }
    } else {
        $error_message = "Judul, Penulis, Tahun Terbit, dan Rak Buku tidak boleh kosong.";
    }
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    if ($stmt = $conn->prepare("SELECT * FROM books WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result_edit = $stmt->get_result();
        if ($result_edit->num_rows > 0) {
            $data = $result_edit->fetch_assoc();
            $title = $data['title'];
            $author = $data['author'];
            $published_year = $data['published_year'];
            $shelf_id = $data['shelf_id'];
            $action = "Edit Buku";
        } else {
            $error_message = "Data buku tidak ditemukan.";
        }
        $stmt->close();
    } else {
        $error_message = "Gagal mempersiapkan statement untuk edit: " . $conn->error;
    }
}

$sql = "SELECT books.id, books.title, books.author, books.published_year, book_shelf.name AS shelf_name 
        FROM books 
        JOIN book_shelf ON books.shelf_id = book_shelf.id";
$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}


$shelf_sql = "SELECT id, name FROM book_shelf";
$shelf_result = $conn->query($shelf_sql);

if (!$shelf_result) {
    die("Query rak buku gagal: " . $conn->error);
}
?>
    
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku LiterateSpace</title>
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

        .form-group option {
            padding: 10px;
            border: 1px solid #7d6eff;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
        }

        .form-group option:focus {
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

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
        }

        .success {
            background-color: #d1e7dd;
            color: #0f5132;
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
            <h1>Daftar Buku LiterateSpace</h1>
        </div>
        <a href="?add=1" class="add-button">
            <img src="../assets/images/logo tambah.png" alt="Tambah Buku">Tambah Buku
        </a>
        <button class="close-btn" onclick="exitPage()">×</button>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="message error">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="message success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>


    <table>
        <thead>
            <tr>
                <th style="width: 40px;">ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Rak Buku</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['published_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['shelf_name']); ?></td>
                    <td>
                        <div class="button-group">
                            <a href="?edit=<?php echo urlencode($row['id']); ?>" class="button edit">
                                <img src="../assets/images/logo edit.png" alt="Edit">Edit
                            </a>
                            <a href="?delete=<?php echo urlencode($row['id']); ?>" class="button delete" onclick="return confirm('Yakin ingin menghapus buku ini?');">
                                <img src="../assets/images/logo hapus.png" alt="Hapus">Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($_GET['add']) || isset($_GET['edit'])): ?>
        <div class="form-container">
            <form action="" method="POST">
                <h2><?php echo htmlspecialchars($action); ?></h2>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <div class="form-group">
                    <label for="title">Judul</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Judul" required>
                </div>
                <div class="form-group">
                    <label for="author">Penulis</label>
                    <input type="text" name="author" value="<?php echo htmlspecialchars($author); ?>" placeholder="Penulis" required>
                </div>
                <div class="form-group">
                    <label for="published_year">Tahun Terbit</label>
                    <input type="number" name="published_year" value="<?php echo htmlspecialchars($published_year); ?>" placeholder="Tahun Terbit" required>
                </div>
                <div class="form-group">
                    <label for="shelf_id">Rak Buku</label>
                    <select name="shelf_id" required>
                        <option value="">-- Pilih Rak Buku --</option>
                        <?php while ($shelf = $shelf_result->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($shelf['id']); ?>" <?php echo ($shelf['id'] == $shelf_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($shelf['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="save">
                        <img src="../assets/images/logo simpan.png" alt="Simpan"> Simpan
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
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

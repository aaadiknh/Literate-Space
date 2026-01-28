<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../db.php';

$add_success = $add_error = "";

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($stmt = $conn->prepare("DELETE FROM borrowed_books WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: detail_peminjaman.php?success_delete=1"); 
            exit();
        } else {
            $add_error = "Gagal menghapus data: " . $stmt->error;
            $stmt->close();
        }
    } else {
        $add_error = "Gagal mempersiapkan statement untuk delete: " . $conn->error;
    }
}


if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $user_id = intval(trim($_POST['user_id']));
    $book_id = intval(trim($_POST['book_id']));
    $borrowed_at = trim($_POST['borrowed_at']);
    $returned_at = trim($_POST['returned_at']);
    $location = trim($_POST['location']);
    $author = trim($_POST['author']);

    if (!validateDate($borrowed_at) || !validateDate($returned_at)) {
        $add_error = "Format tanggal tidak valid.";
    } else {
        $sql = "UPDATE borrowed_books SET user_id = ?, book_id = ?, borrowed_at = ?, returned_at = ?, location = ?, author = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iissssi", $user_id, $book_id, $borrowed_at, $returned_at, $location, $author, $id);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: detail_peminjaman.php?success_update=1");
                exit();
            } else {
                $add_error = "Gagal mengupdate data: " . $stmt->error;
                $stmt->close();
            }
        } else {
            $add_error = "Gagal mempersiapkan statement untuk update: " . $conn->error;
        }
    }
}

if (isset($_POST['add_borrow'])) {
    $user_id = intval(trim($_POST['user_id']));
    $book_id = intval(trim($_POST['book_id']));
    $borrowed_at = trim($_POST['borrowed_at']);
    $returned_at = trim($_POST['returned_at']);
    $location = trim($_POST['location']);
    $author = trim($_POST['author']);

    if (empty($user_id) || empty($book_id) || empty($borrowed_at) || empty($returned_at) || empty($location) || empty($author)) {
        $add_error = "Semua field harus diisi.";
    } elseif (!validateDate($borrowed_at) || !validateDate($returned_at)) {
        $add_error = "Format tanggal tidak valid.";
    } else {

        if ($stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id, borrowed_at, returned_at, location, author) VALUES (?, ?, ?, ?, ?, ?)")) {
            $stmt->bind_param("iissss", $user_id, $book_id, $borrowed_at, $returned_at, $location, $author);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: detail_peminjaman.php?success_add=1"); 
                exit();
            } else {
                $add_error = "Gagal menambahkan data peminjaman: " . $stmt->error;
                $stmt->close();
            }
        } else {
            $add_error = "Gagal mempersiapkan statement untuk menambah data peminjaman: " . $conn->error;
        }
    }
}


if (isset($_GET['success_add'])) {
    $add_success = "Data peminjaman berhasil ditambahkan.";
}
if (isset($_GET['success_update'])) {
    $add_success = "Data peminjaman berhasil diupdate.";
}
if (isset($_GET['success_delete'])) {
    $add_success = "Data peminjaman berhasil dihapus.";
}

$edit_user_id = $edit_book_id = $edit_borrowed_at = $edit_returned_at = $edit_location = $edit_author = "";
$edit_id = 0;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $sql_edit = "SELECT * FROM borrowed_books WHERE id = ?";
    if ($stmt_edit = $conn->prepare($sql_edit)) {
        $stmt_edit->bind_param("i", $edit_id);
        if ($stmt_edit->execute()) {
            $result_edit = $stmt_edit->get_result();
            if ($result_edit->num_rows > 0) {
                $edit_row = $result_edit->fetch_assoc();
                $edit_user_id = $edit_row['user_id'];
                $edit_book_id = $edit_row['book_id'];
                $edit_borrowed_at = $edit_row['borrowed_at'];
                $edit_returned_at = $edit_row['returned_at'];
                $edit_location = $edit_row['location'];
                $edit_author = $edit_row['author'];
            } else {
                $add_error = "Data tidak ditemukan.";
            }
        } else {
            $add_error = "Gagal mengeksekusi query edit: " . $stmt_edit->error;
        }
        $stmt_edit->close();
    } else {
        $add_error = "Gagal mempersiapkan statement untuk edit: " . $conn->error;
    }
}

$sql = "SELECT borrowed_books.id, borrowed_books.user_id, borrowed_books.book_id, borrowed_books.borrowed_at, borrowed_books.returned_at, borrowed_books.location, borrowed_books.author, users.username AS username_user, books.title AS title_book
        FROM borrowed_books
        JOIN users ON borrowed_books.user_id = users.id
        JOIN books ON borrowed_books.book_id = books.id";
$result = $conn->query($sql);

if (!$result) {
    $add_error = "Query gagal: " . $conn->error;
    $result = null;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman LiterateSpace</title>
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
            position: relative; /* Untuk posisi tombol close */
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
            position: relative;
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

        .close-btn {
            position: absolute;
            top: 30px;
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

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-text">
            <h1>Detail Peminjaman LiterateSpace</h1>
        </div>
        <a href="?add=1" class="add-button">
            <img src="../assets/images/logo tambah.png" alt="Tambah Rak Buku">Tambah Peminjam</a>
        <button class="close-btn" onclick="exitPage()">×</button>
    </div>

    <?php if (!empty($add_success)): ?>
        <div class="message success">
            <?php echo htmlspecialchars($add_success); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($add_error)): ?>
        <div class="message error">
            <?php echo htmlspecialchars($add_error); ?>
        </div>
    <?php endif; ?>

    <?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">ID</th>
                <th style="width: 50px;">User ID</th>
                <th style="width: 60px;">Nama User</th>
                <th>Judul Buku</th>
                <th style="width: 100px;">Tanggal Peminjaman</th>
                <th style="width: 110px;">Tanggal Pengembalian</th>
                <th style="width: 60px;">Lokasi</th>
                <th style="width: 90px;">Pengarang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username_user']); ?></td>
                    <td><?php echo htmlspecialchars($row['title_book']); ?></td>
                    <td><?php echo htmlspecialchars($row['borrowed_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['returned_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td class="button-group">
                        <a href="?edit=<?php echo $row['id']; ?>" class="button"><img src="../assets/images/logo edit.png" alt=""> Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><img src="../assets/images/logo hapus.png" alt=""> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Tidak ada data peminjaman.</p>
    <?php endif; ?>

    <?php if (isset($_GET['add']) || isset($_GET['edit'])): ?>
    <div class="form-container">
        <form method="POST">
            <h2><?php echo isset($_GET['edit']) ? 'Edit Peminjaman' : 'Tambah Peminjaman'; ?></h2>
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required value="<?php echo htmlspecialchars($edit_user_id); ?>" min="1">
            </div>
            <div class="form-group">
                <label for="book_id">Book ID:</label>
                <input type="number" id="book_id" name="book_id" required value="<?php echo htmlspecialchars($edit_book_id); ?>" min="1">
            </div>
            <div class="form-group">
                <label for="borrowed_at">Tanggal Peminjaman:</label>
                <input type="date" id="borrowed_at" name="borrowed_at" required value="<?php echo htmlspecialchars($edit_borrowed_at); ?>">
            </div>
            <div class="form-group">
                <label for="returned_at">Tanggal Pengembalian:</label>
                <input type="date" id="returned_at" name="returned_at" required value="<?php echo htmlspecialchars($edit_returned_at); ?>">
            </div>
            <div class="form-group">
                <label for="location">Lokasi:</label>
                <input type="text" id="location" name="location" required value="<?php echo htmlspecialchars($edit_location); ?>">
            </div>
            <div class="form-group">
                <label for="author">Pengarang:</label>
                <input type="text" id="author" name="author" required value="<?php echo htmlspecialchars($edit_author); ?>">
            </div>
            <div class="form-group">
                <?php if (isset($_GET['edit'])): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_id); ?>">
                    <button type="submit" name="save"> <img src="../assets/images/logo tambah.png" alt=""> Update</button>
                <?php else: ?>
                    <button type="submit" name="add_borrow"> <img src="../assets/images/logo tambah.png" alt=""> Tambah</button>
                <?php endif; ?>
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

<?php

require_once '../db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($stmt = $conn->prepare("DELETE FROM users WHERE id = ?")) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: detail_user.php"); 
        exit();
    } else {
        die("Gagal mempersiapkan statement untuk delete: " . $conn->error);
    }
}

if (isset($_POST['save'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, password = ?, email = ?, role = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssi", $username, $hashed_password, $email, $role, $id);
            $stmt->execute();
            $stmt->close();
            header("Location: detail_user.php");
            exit();
        } else {
            die("Gagal mempersiapkan statement untuk update: " . $conn->error);
        }
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssi", $username, $email, $role, $id);
            $stmt->execute();
            $stmt->close();
            header("Location: detail_user.php"); 
            exit();
        } else {
            die("Gagal mempersiapkan statement untuk update tanpa password: " . $conn->error);
        }
    }
}

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $role = 'member';

    if (empty($username) || empty($password) || empty($email)) {
        $add_error = "Semua field harus diisi.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
            if ($stmt->execute()) {
                $add_success = "User baru berhasil ditambahkan.";
            } else {
                if ($conn->errno === 1062) { 
                    $add_error = "Username atau email sudah ada. Silakan gunakan yang lain.";
                } else {
                    $add_error = "Gagal menambahkan user: " . $stmt->error;
                }
            }
            $stmt->close();
        } else {
            $add_error = "Gagal mempersiapkan statement untuk menambah user: " . $conn->error;
        }
    }
}

$sql = "SELECT id, username, password, email, role FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User LiterateSpace</title>
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
            <h1>Detail User LiterateSpace</h1>
        </div>
        <a href="?add=1" class="add-button">
            <img src="../assets/images/logo add user.png" alt="Tambah">Tambah User
        </a>
        <button class="close-btn" onclick="exitPage()">×</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["id"]) . "</td>
                        <td>" . htmlspecialchars($row["username"]) . "</td>
                        <td>" . htmlspecialchars($row["password"]) . "</td> <!-- Tampilkan Password -->
                        <td>" . htmlspecialchars($row["email"]) . "</td>
                        <td>" . htmlspecialchars($row["role"]) . "</td>
                        <td>
                            <div class='button-group'>
                                <a href='?edit=" . urlencode($row['id']) . "' class='button'>
                                    <img src='../assets/images/logo edit.png' alt='Edit'>Edit
                                </a>
                                <a href='?delete=" . urlencode($row['id']) . "' class='button' onclick=\"return confirm('Apakah Anda yakin ingin menghapus user ini?');\">
                                    <img src='../assets/images/logo hapus.png' alt='Hapus'>Hapus
                                </a>
                            </div>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data ditemukan</td></tr>";
        }
        ?>
        </tbody>
    </table>


    <?php

    if (isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        if ($stmt = $conn->prepare("SELECT * FROM users WHERE id = ?")) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result_edit = $stmt->get_result();
            if ($result_edit->num_rows > 0) {
                $data = $result_edit->fetch_assoc();
            } else {
                echo "<p style='color: red;'>Data user tidak ditemukan.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Gagal mempersiapkan statement untuk edit: " . htmlspecialchars($conn->error) . "</p>";
        }

        if (isset($data)) {
    ?>
        <div class="form-container">
            <form action="" method="POST">
                <h2>Edit User</h2>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password (biarkan kosong jika tidak diubah)">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" name="role" value="<?php echo htmlspecialchars($data['role']); ?>" placeholder="Role" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="save">
                        <img src="../assets/images/logo simpan.png" alt="Simpan">Simpan
                    </button>
                </div>
            </form>
        </div>
    <?php
        }
    }
    ?>

    <?php

    if (isset($_GET['add'])) {
    ?>
        <div class="form-container">
            <form action="" method="POST">
                <h2>Tambah User</h2>
                <?php
                if (isset($add_error)) {
                    echo "<p style='color: red;'>" . htmlspecialchars($add_error) . "</p>";
                }
                if (isset($add_success)) {
                    echo "<p style='color: green;'>" . htmlspecialchars($add_success) . "</p>";
                }
                ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                    <input type="hidden" name="role" value="member">
                <div class="form-group">
                    <button type="submit" name="add_user">
                        <img src="../assets/images/logo add user.png" alt="Tambah">Tambah
                    </button>
                </div>
            </form>
        </div>
    <?php
    }
    ?>

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

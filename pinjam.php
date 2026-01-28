<?php  

include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$stmt_user = $conn->prepare("SELECT id FROM users WHERE username=?");
if ($stmt_user) {
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $userResult = $stmt_user->get_result();
    $user = $userResult->fetch_assoc();
    $user_id = $user['id']; 
    $stmt_user->close();
} else {
    die("Error preparing user query: " . $conn->error);
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_title = trim($_POST['book_title']);
    $author = trim($_POST['author']);
    $published_year = intval($_POST['published_year']);
    $borrowed_at_input = trim($_POST['borrowed_at']);

    $date = DateTime::createFromFormat('Y-m-d', $borrowed_at_input);
    if (!$date || $date->format('Y-m-d') !== $borrowed_at_input) {
        $error_message = "Format tanggal pinjam tidak valid.";
    } else {
        $borrowed_at = $borrowed_at_input; 
        $returned_at = date('Y-m-d', strtotime('+7 days', strtotime($borrowed_at))); 

        $stmt_book = $conn->prepare("SELECT id, shelf_id FROM books WHERE title=? AND author=? AND published_year=?");
        if ($stmt_book) {
            $stmt_book->bind_param("ssi", $book_title, $author, $published_year);
            $stmt_book->execute();
            $bookResult = $stmt_book->get_result();

            if ($bookResult->num_rows > 0) {
                $book = $bookResult->fetch_assoc();
                $book_id = $book['id'];
                $shelf_id = $book['shelf_id'];

                $stmt_shelf = $conn->prepare("SELECT name FROM book_shelf WHERE id=?");
                if ($stmt_shelf) {
                    $stmt_shelf->bind_param("i", $shelf_id);
                    $stmt_shelf->execute();
                    $shelfResult = $stmt_shelf->get_result();
                    $shelf = $shelfResult->fetch_assoc();
                    $location = $shelf['name'];
                    $stmt_shelf->close();
                } else {
                    $error_message = "Error preparing shelf query: " . $conn->error;
                }

                if (empty($error_message)) {

                    $stmt_insert = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id, borrowed_at, returned_at, location) VALUES (?, ?, ?, ?, ?)");
                    if ($stmt_insert) {
                        $stmt_insert->bind_param(
                            "iisss", 
                            $user_id, 
                            $book_id, 
                            $borrowed_at, 
                            $returned_at, 
                            $location
                        );
                        
                        if ($stmt_insert->execute()) {
                            echo "<script>
                                    alert('Hallo $username! kamu berhasil meminjam buku di LiterateSpace. Silakan kembalikan bukunya sebelum $returned_at, Selamat Berliterasi!');
                                    window.location.href = 'index.php';
                                </script>";
                            exit(); 
                        } else {
                            $error_message = "Gagal melakukan peminjaman: " . $stmt_insert->error;
                        }
                        $stmt_insert->close();
                    } else {
                        $error_message = "Error preparing insert statement: " . $conn->error;
                    }
                }
            } else {
                $error_message = "Buku tidak tersedia di daftar buku LiterateSpace.";
            }
            $stmt_book->close();
        } else {
            $error_message = "Error preparing book query: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Buku - LiterateSpace</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .Borrow-container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 12px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border: 2px solid #CBCBFF;
        }

        .Borrow-image {
            width: 100%;
            max-width: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .Borrow-image img {
            width: 100%;
            max-width: 400px;
            height: auto;
        }

        .Borrow-form {
            width: 100%;
            padding: 40px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
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

        .title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #CBCBFF;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .Borrow-btn {
            width: 100%;
            padding: 12px;
            background-color: #6A5ACD;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }

        .Borrow-btn:hover {
            background-color: #5b4acb;
        }

        .message {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        @media (min-width: 768px) {
            .Borrow-container {
                flex-wrap: nowrap;
            }
            .Borrow-image, .Borrow-form {
                width: 48%;
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="Borrow-container">
        <div class="Borrow-image">
            <img src="assets/images/pinjam.png" alt="Borrow illustration">
        </div>
        <div class="Borrow-form">
            <button class="close-btn" onclick="window.location.href='index.php'">&times;</button>
            <h1 class="title">Keep the Book Now!</h1>
            <p class="subtitle">Hello, let’s engage in literacy and 
            borrow books at LiterateSpace!</p>

            <?php if ($success_message): ?>
                <div class="message success"><?= $success_message ?></div>
            <?php elseif ($error_message): ?>
                <div class="message error"><?= $error_message ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <input type="text" name="book_title" placeholder="Judul Buku" required>
                </div>
                <div class="form-group">
                    <input type="text" name="author" placeholder="Pengarang" required>
                </div>
                <div class="form-group">
                    <input type="number" name="published_year" placeholder="Tahun Terbit" required>
                </div>
                <div class="form-group">
                    <input type="date" name="borrowed_at" required>
                </div>
                <button type="submit" class="Borrow-btn">Pinjam Buku</button>
            </form>
        </div>
    </div>
</body>
</html>

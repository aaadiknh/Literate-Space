<?php

include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            border: 2px solid #CBCBFF;
        }

        .login-image {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-image img {
            width: 100%;
            max-width: 400px;
            height: auto;
        }

        .login-form {
            width: 50%;
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
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }

        .subtitle {
            font-size: 16px;
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

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #6A5ACD;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #5b4acb;
        }

        .signup-text {
            text-align: center;
            margin-top: 20px;
        }

        .signup-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <img src="assets/images/gambar login.png" alt="Login illustration">
        </div>
        <div class="login-form">
            <button class="close-btn" onclick="exitPage()">×</button>
            <h1 class="title">Hello Again!</h1>
            <p class="subtitle">Welcome back, you've been missed!</p>

            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="submit" name="login" value="LOGIN" class="login-btn">
                </div>
            </form>
            <div class="signup-text">
                <p>Belum punya akun? <a href="regist.php" class="signup-link">Daftar Disini</a></p>
            </div>
        </div>
    </div>
    <script>
        function exitPage() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>

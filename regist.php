<?php
session_start(); 
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $checkQuery = "SELECT * FROM users WHERE username='$username'";
    $checkResult = $conn->query($checkQuery);
    
    if ($checkResult->num_rows == 0) {

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if ($conn->query($insertQuery) === TRUE) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            
            header("Location: login.php"); 
            exit();
        } else {
            $error = "Gagal mendaftar: " . $conn->error;
        }
    } else {
        $error = "Username sudah terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 3px solid #CBCBFF;
        }

        .left-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .left-section img {
            max-width: 80%;
        }

        .right-section {
            width: 50%;
            padding: 30px;
            position: relative;
        }

        .right-section h2 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .right-section p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
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

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #6A5ACD;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #5b4acb;
        }

        .login-text {
            margin-top: 20px;
            text-align: center;
        }

        .login-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            line-height: 0;
            border: none;
            cursor: pointer;
        }

        .close-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="assets/images/gambar register.png" alt="Gambar Registrasi">
        </div>
        <div class="right-section">
            <button class="close-btn" onclick="window.location.href = 'login.php';">×</button>
            <h2>Registrasi New Member</h2>
            <p>Hallo! Welcome to LiterateSpace, I hope you enjoy it!</p>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="submit-btn">SIGN UP</button>
            </form>
            <div class="login-text">
                <p>Sudah punya akun? <a href="login.php" class="login-link">Login Disini</a></p>
            </div>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </div>
    </div>
</body>
</html>

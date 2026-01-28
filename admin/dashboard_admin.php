<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin LiterateSpace</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    /* Flexbox untuk header */
    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 40px;
    }

    .title-container {
      text-align: left;
      margin-right: 140px;

    }

    .title {
      font-size: 36px;
      font-weight: bold;
      margin: 0;
      color: #000;
      margin-top: 80px;
    }

    .subtitle {
      font-size: 16px;
      color: #666;
      margin: 5px 0;
      margin-top: 15px;
    }

    .ilustration img {
      width: 340px;
      height: auto;
      padding-top: 140px;
      margin-bottom: 30px;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px; 
      padding: 20px;
    }

    .grid-item {
      background-color: white;
      border-radius: 12px;
      padding: 30px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .grid-item:hover {
      transform: scale(1.05);
    }

    .grid-item img {
      width: 70px;
      height: 70px; 
      margin-right: 20px;
    }

    .grid-item a {
      text-decoration: none;
      font-size: 22px;
      font-weight: bold;
      color: #fff;
    }

    .grid-item.detail-pengguna {
      background-color: #c1a4f7;
    }

    .grid-item.detail-peminjaman {
      background-color: #f7a4d0;
    }

    .grid-item.daftar-buku {
      background-color: #f7b4a4;
    }

    .grid-item.rak-buku {
      background-color: #a4bff7;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title-container">
        <div class="title">LiterateSpace</div>
        <div class="subtitle">"Bersama LiterateSpace,<br>Menciptakan Peluang Literasi!"</div>
      </div>
      <div class="ilustration">
        <img src="../assets/images/admin.png" alt="Admin Illustration">
      </div>
    </div>
    <div class="grid">
      <div class="grid-item detail-pengguna">
        <img src="../assets/images/users.png" alt="Detail Pengguna Icon">
        <a href="detail_user.php">Detail Pengguna</a>
      </div>
      <div class="grid-item daftar-buku">
        <img src="../assets/images/buku.png" alt="Daftar Buku Icon">
        <a href="daftar_book.php">Daftar Buku</a>
      </div>
      <div class="grid-item detail-peminjaman">
        <img src="../assets/images/peminjaman.png" alt="Detail Peminjaman Icon">
        <a href="detail_peminjaman.php">Detail Peminjaman</a>
      </div>
      <div class="grid-item rak-buku">
        <img src="../assets/images/rak buku.png" alt="Rak Buku Icon">
        <a href="rak_book.php">Rak Buku</a>
      </div>
    </div>
  </div>
</body>
</html>

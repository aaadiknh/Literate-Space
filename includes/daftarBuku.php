<?php

require_once 'db.php';


$sql = "SELECT books.title, books.author, books.published_year, book_shelf.name AS shelf_name 
        FROM books 
        JOIN book_shelf ON books.shelf_id = book_shelf.id";
$result = $conn->query($sql);

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
            margin-top: 0px;
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

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
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
            <h1>Daftar Buku LiterateSpace</h1>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Rak Buku</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["title"]) . "</td>
                        <td>" . htmlspecialchars($row["author"]) . "</td>
                        <td>" . htmlspecialchars($row["published_year"]) . "</td>
                        <td>" . htmlspecialchars($row["shelf_name"]) . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data ditemukan</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close();
?>

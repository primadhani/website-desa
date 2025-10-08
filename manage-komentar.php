<?php
require_once 'auth.php';
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql_delete = "DELETE FROM komentar WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    try {
        $stmt_delete->execute([$delete_id]);
        echo "<script>alert('Komentar berhasil dihapus!'); window.location.href='manage-komentar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal menghapus komentar: " . $e->getMessage() . "');</script>";
    }
}

$sql_select = "SELECT 
                    k.id, 
                    k.nama, 
                    k.pesan, 
                    k.tanggal_kirim, 
                    b.judul AS judul_berita 
                FROM 
                    komentar k
                JOIN 
                    berita b ON k.id_berita = b.id
                ORDER BY 
                    k.tanggal_kirim DESC";
$stmt_select = $pdo->query($sql_select);
$komentar = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Komentar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="aset/favicon.png" type="image/png">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .page-container {
            display: flex;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            box-sizing: border-box;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .aksi a {
            margin-right: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .aksi a.edit { color: #2980b9; }
        .aksi a.hapus { color: #c0392b; }
        .no-komentar {
            text-align: center;
            color: #888;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .page-container {
                flex-direction: column;
            }
            h1 {
                font-size: 1.5rem;
            }
            .card {
                padding: 15px;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                background: #fff;
                box-shadow: 0px 2px 5px rgba(0,0,0,0.05);
            }
            td {
                border: none;
                padding: 6px 8px;
                text-align: left;
            }
            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 4px;
                color: #34495e;
            }
            .aksi {
                margin-top: 8px;
            }
            .btn-delete {
                display: inline-block;
                background: #c0392b;
                color: white;
                padding: 6px 12px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
            }
            .btn-delete:hover {
                background: #e74c3c;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <?php include 'sidebar.php'; ?> 
        <div class="main-content">
            <h1>Kelola Komentar</h1>
            <div class="card">
                <h2>Daftar Komentar</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Komentar</th>
                            <th>Berita</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($komentar) > 0): ?>
                            <?php foreach ($komentar as $row): ?>
                                <tr>
                                    <td data-label="Nama"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td data-label="Komentar"><?php echo htmlspecialchars($row['pesan']); ?></td>
                                    <td data-label="Berita"><?php echo htmlspecialchars($row['judul_berita']); ?></td>
                                    <td data-label="Tanggal"><?php echo date('d M Y, H:i', strtotime($row['tanggal_kirim'])); ?></td>
                                    <td data-label="Aksi" class="aksi">
                                        <form action="manage-komentar.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
                                            <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <button type="submit" class="btn-delete">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-komentar">Tidak ada komentar yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
require_once 'auth.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }

    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);

    try {
        $sql = "INSERT INTO berita (judul, isi_berita, gambar) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$judul, $isi_berita, $gambar]);
    } catch (PDOException $e) {
        die("Error saat tambah berita: " . $e->getMessage());
    }

    header("Location: manage-berita.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    try {
        $sql_gambar = "SELECT gambar FROM berita WHERE id = ?";
        $stmt_gambar = $pdo->prepare($sql_gambar);
        $stmt_gambar->execute([$id]);
        $row_gambar = $stmt_gambar->fetch(PDO::FETCH_ASSOC);

        if ($row_gambar && $row_gambar['gambar']) {
            $file_path = "uploads/" . $row_gambar['gambar'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $sql = "DELETE FROM berita WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error saat hapus berita: " . $e->getMessage());
    }

    header("Location: manage-berita.php");
    exit();
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        $sql = "SELECT * FROM berita WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error saat ambil data edit: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi_berita = $_POST['isi_berita'];
    $gambar_lama = $_POST['gambar_lama'];
    $gambar_baru = $_FILES['gambar']['name'];

    try {
        if ($gambar_baru) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
            move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);

            if ($gambar_lama && file_exists("uploads/" . $gambar_lama)) {
                unlink("uploads/" . $gambar_lama);
            }

            $sql = "UPDATE berita SET judul = ?, isi_berita = ?, gambar = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi_berita, $gambar_baru, $id]);
        } else {
            $sql = "UPDATE berita SET judul = ?, isi_berita = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi_berita, $id]);
        }
    } catch (PDOException $e) {
        die("Error saat update berita: " . $e->getMessage());
    }

    header("Location: manage-berita.php");
    exit();
}

try {
    $sql_tabel = "SELECT id, judul, gambar, tanggal_dibuat FROM berita ORDER BY tanggal_dibuat DESC";
    $stmt_tabel = $pdo->query($sql_tabel);
    $berita_list = $stmt_tabel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error saat ambil daftar berita: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="aset/favicon.png" type="image/png">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .main-content {
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        input, textarea, button {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
        }
        button {
            background: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #2ecc71;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .aksi a {
            margin-right: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .aksi a.edit { color: #2980b9; }
        .aksi a.hapus { color: #c0392b; }
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                align-items: stretch;
            }
            .main-content { padding: 15px; }
            h1 { font-size: 1.5rem; text-align: center; }
            .card { padding: 15px; }
            input, textarea, button { font-size: 14px; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                background: #fff;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
            td {
                border: none;
                padding: 6px 8px;
                text-align: left;
                position: relative;
            }
            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 4px;
                color: #34495e;
            }
            .aksi {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
            }
            .aksi a {
                flex: 1;
                min-width: 70px;
                padding: 6px 10px;
                border-radius: 6px;
                text-align: center;
                font-size: 14px;
            }
            .aksi a.edit { background: #2980b9; color: white; }
            .aksi a.hapus { background: #c0392b; color: white; }
            img { max-width: 100%; height: auto; }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h1>Manajemen Berita</h1>
        <div class="card">
            <h2><?php echo $edit_data ? 'Edit Berita' : 'Tambah Berita Baru'; ?></h2>
            <form action="manage-berita.php" method="POST" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                    <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($edit_data['gambar']); ?>">
                <?php endif; ?>
                <label>Judul</label>
                <input type="text" name="judul" value="<?php echo htmlspecialchars($edit_data['judul'] ?? ''); ?>" required>
                <label>Isi Berita</label>
                <textarea name="isi_berita" rows="5" required><?php echo htmlspecialchars($edit_data['isi_berita'] ?? ''); ?></textarea>
                <label>Gambar</label>
                <input type="file" name="gambar" accept="image/*">
                <?php if ($edit_data && $edit_data['gambar']): ?>
                    <p>Gambar saat ini: <img src="uploads/<?php echo htmlspecialchars($edit_data['gambar']); ?>" width="100"></p>
                <?php endif; ?>
                <button type="submit" name="<?php echo $edit_data ? 'update' : 'tambah'; ?>">Simpan</button>
            </form>
        </div>
        <div class="card">
            <h2>Daftar Berita</h2>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Gambar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($berita_list) {
                        foreach ($berita_list as $row) {
                            echo "<tr>";
                            echo "<td data-label='Judul'>" . htmlspecialchars($row['judul']) . "</td>";
                            echo "<td data-label='Gambar'>";
                            if ($row['gambar']) {
                                echo "<img src='uploads/" . htmlspecialchars($row['gambar']) . "' width='80'>";
                            }
                            echo "</td>";
                            echo "<td data-label='Tanggal'>" . htmlspecialchars($row['tanggal_dibuat']) . "</td>";
                            echo "<td data-label='Aksi' class='aksi'>
                                <a href='manage-berita.php?edit=" . htmlspecialchars($row['id']) . "' class='edit'>Edit</a>
                                <a href='manage-berita.php?hapus=" . htmlspecialchars($row['id']) . "' class='hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada berita.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

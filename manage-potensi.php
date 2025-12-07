<?php
require_once 'auth.php';
require_once 'config.php';

// --- LOGIKA TAMBAH POTENSI BARU ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    // Ambil tanggal dari form TAMBAH
    $tanggal_dibuat = $_POST['tanggal_dibuat']; 
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }

    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

    try {
        // Query INSERT dengan tanggal
        $sql = "INSERT INTO potensi (judul, isi, foto, tanggal_dibuat) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$judul, $isi, $foto, $tanggal_dibuat]);
    } catch (PDOException $e) {
        die("Error saat tambah potensi: " . $e->getMessage());
    }

    header("Location: manage-web.php");
    exit();
}

// --- LOGIKA HAPUS POTENSI ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    try {
        $sql_foto = "SELECT foto FROM potensi WHERE id = ?";
        $stmt_foto = $pdo->prepare($sql_foto);
        $stmt_foto->execute([$id]);
        $row_foto = $stmt_foto->fetch(PDO::FETCH_ASSOC);

        if ($row_foto && $row_foto['foto']) {
            $file_path = "uploads/" . $row_foto['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $sql = "DELETE FROM potensi WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error saat hapus potensi: " . $e->getMessage());
    }

    header("Location: manage-web.php");
    exit();
}

// --- LOGIKA AMBIL DATA UNTUK EDIT ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        $sql = "SELECT * FROM potensi WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error saat ambil data edit: " . $e->getMessage());
    }
}

// --- LOGIKA UPDATE POTENSI ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    // Ambil tanggal dari form UPDATE
    $tanggal_dibuat = $_POST['tanggal_dibuat']; 
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $_FILES['foto']['name'];

    try {
        if ($foto_baru) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

            if ($foto_lama && file_exists("uploads/" . $foto_lama)) {
                unlink("uploads/" . $foto_lama);
            }

            // Query UPDATE dengan foto baru dan tanggal baru
            $sql = "UPDATE potensi SET judul = ?, isi = ?, foto = ?, tanggal_dibuat = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi, $foto_baru, $tanggal_dibuat, $id]);
        } else {
            // Query UPDATE tanpa foto baru, tapi dengan tanggal baru
            $sql = "UPDATE potensi SET judul = ?, isi = ?, tanggal_dibuat = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$judul, $isi, $tanggal_dibuat, $id]);
        }
    } catch (PDOException $e) {
        die("Error saat update potensi: " . $e->getMessage());
    }

    header("Location: manage-web.php");
    exit();
}

// --- LOGIKA AMBIL DAFTAR POTENSI UNTUK TABEL ---
try {
    $sql_tabel = "SELECT id, judul, foto, tanggal_dibuat FROM potensi ORDER BY tanggal_dibuat DESC";
    $stmt_tabel = $pdo->query($sql_tabel);
    $potensi_list = $stmt_tabel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error saat ambil daftar potensi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Potensi</title>
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
            max-width: 1200px;
            box-sizing: border-box;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }
        input, textarea, button {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
        }
        label {
             display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        button {
            background: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #2ecc71; }
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
                box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
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
        <h1>Manajemen Potensi</h1>
        <div class="card">
            <h2><?php echo $edit_data ? 'Edit Potensi' : 'Tambah Potensi Baru'; ?></h2>
            <form action="manage-web.php" method="POST" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                    <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($edit_data['foto']); ?>">
                <?php endif; ?>
                
                <label>Judul</label>
                <input type="text" name="judul" value="<?php echo htmlspecialchars($edit_data['judul'] ?? ''); ?>" required>
                
                <label>Isi Potensi</label>
                <textarea name="isi" rows="5" required><?php echo htmlspecialchars($edit_data['isi'] ?? ''); ?></textarea>
                
                <label>Tanggal Dibuat</label>
                <input type="datetime-local" name="tanggal_dibuat" 
                       value="<?php 
                           if ($edit_data && $edit_data['tanggal_dibuat']) {
                               // Format tanggal dari DB (biasanya YYYY-MM-DD HH:MM:SS) ke format yang dibutuhkan oleh input datetime-local (YYYY-MM-DDTHH:MM)
                               echo date('Y-m-d\TH:i', strtotime($edit_data['tanggal_dibuat']));
                           } else {
                               // Untuk Tambah Baru, set default ke waktu saat ini
                               echo date('Y-m-d\TH:i');
                           }
                       ?>" 
                       required>
                
                <label>Foto</label>
                <input type="file" name="foto" accept="image/*">
                
                <?php if ($edit_data && $edit_data['foto']): ?>
                    <p>Foto saat ini: <img src="uploads/<?php echo htmlspecialchars($edit_data['foto']); ?>" width="100"></p>
                <?php endif; ?>
                
                <button type="submit" name="<?php echo $edit_data ? 'update' : 'tambah'; ?>">Simpan</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Daftar Potensi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($potensi_list) {
                        foreach ($potensi_list as $row) {
                            echo "<tr>";
                            echo "<td data-label='Judul'>" . htmlspecialchars($row['judul']) . "</td>";
                            echo "<td data-label='Foto'>";
                            if ($row['foto']) {
                                echo "<img src='uploads/" . htmlspecialchars($row['foto']) . "' width='80'>";
                            }
                            echo "</td>";
                            echo "<td data-label='Tanggal'>" . htmlspecialchars($row['tanggal_dibuat']) . "</td>";
                            echo "<td data-label='Aksi' class='aksi'>
                                <a href='manage-web.php?edit=" . htmlspecialchars($row['id']) . "' class='edit'>Edit</a>
                                <a href='manage-web.php?hapus=" . htmlspecialchars($row['id']) . "' class='hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada potensi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
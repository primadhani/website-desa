<?php
// Cek login dan panggil konfigurasi database
require_once 'auth.php';
require_once 'config.php';

// --- Ambil jenis lembaga yang sudah terpakai untuk menonaktifkan di form Tambah ---
try {
    $sql_used_jenis = "SELECT DISTINCT jenis_lembaga FROM lembaga_desa";
    $stmt_used_jenis = $pdo->query($sql_used_jenis);
    $used_jenis_lembaga = $stmt_used_jenis->fetchAll(PDO::FETCH_COLUMN, 0);
} catch (PDOException $e) {
    // Jika tabel belum ada, atau error, anggap tidak ada yang terpakai
    $used_jenis_lembaga = []; 
}

// --- Tambah Lembaga Baru (Termasuk Visi & Misi) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama_lembaga = $_POST['nama_lembaga'];
    $jenis_lembaga = $_POST['jenis_lembaga'];
    $nama_ketua = $_POST['nama_ketua'];
    $periode = $_POST['periode'];
    $visi = $_POST['visi']; // FIELD BARU
    $misi = $_POST['misi']; // FIELD BARU
    $logo = '';

    // Upload logo jika ada
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $logo = basename($_FILES["logo"]["name"]);
        $target_file = $target_dir . $logo;
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
    }

    try {
        // Query INSERT diperbarui untuk menyertakan visi dan misi
        $sql = "INSERT INTO lembaga_desa (nama_lembaga, jenis_lembaga, nama_ketua, periode, visi, misi, logo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama_lembaga, $jenis_lembaga, $nama_ketua, $periode, $visi, $misi, $logo]);
    } catch (PDOException $e) {
        // Error handling minimal sesuai template asli
    }

    header("Location: manage-lembaga.php");
    exit();
}

// --- Hapus Lembaga ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    try {
        // Ambil nama file logo terlebih dahulu
        $sql_logo = "SELECT logo FROM lembaga_desa WHERE id = ?";
        $stmt_logo = $pdo->prepare($sql_logo);
        $stmt_logo->execute([$id]);
        $row_logo = $stmt_logo->fetch(PDO::FETCH_ASSOC);

        // Hapus file logo jika ada
        if ($row_logo && $row_logo['logo']) {
            $file_path = "uploads/" . $row_logo['logo'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data dari database
        $sql = "DELETE FROM lembaga_desa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

    } catch (PDOException $e) {
        // Error handling minimal sesuai template asli
    }

    header("Location: manage-lembaga.php");
    exit();
}

// --- Ambil data untuk Edit ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        // SELECT * mengambil semua kolom, termasuk visi dan misi
        $sql = "SELECT * FROM lembaga_desa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Error handling minimal sesuai template asli
    }
}

// --- Update Lembaga (Termasuk Visi & Misi) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_lembaga = $_POST['nama_lembaga'];
    $jenis_lembaga = $_POST['jenis_lembaga'];
    $nama_ketua = $_POST['nama_ketua'];
    $periode = $_POST['periode'];
    $visi = $_POST['visi']; // FIELD BARU
    $misi = $_POST['misi']; // FIELD BARU
    $logo_lama = $_POST['logo_lama'];
    $logo_hapus = isset($_POST['hapus_logo']);
    $logo_baru = $_FILES['logo']['name'];

    try {
        $update_logo = $logo_lama;

        // Logika penghapusan logo
        if ($logo_hapus && !$logo_baru) {
            if ($logo_lama && file_exists("uploads/" . $logo_lama)) {
                unlink("uploads/" . $logo_lama);
            }
            $update_logo = NULL;
        } 
        // Logika upload logo baru
        elseif ($logo_baru) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["logo"]["name"]);
            move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

            if ($logo_lama && file_exists("uploads/" . $logo_lama)) {
                unlink("uploads/" . $logo_lama);
            }
            $update_logo = $logo_baru;
        }
        
        // Cek apakah ada perubahan pada logo (hapus atau upload baru)
        if ($logo_hapus || $logo_baru) {
            // Query UPDATE LENGKAP: dengan logo baru/NULL, visi, dan misi
            $sql = "UPDATE lembaga_desa SET nama_lembaga = ?, jenis_lembaga = ?, nama_ketua = ?, periode = ?, visi = ?, misi = ?, logo = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_lembaga, $jenis_lembaga, $nama_ketua, $periode, $visi, $misi, $update_logo, $id]);
        } else {
            // Query UPDATE SEDERHANA: tanpa perubahan logo, tapi dengan visi dan misi
            $sql = "UPDATE lembaga_desa SET nama_lembaga = ?, jenis_lembaga = ?, nama_ketua = ?, periode = ?, visi = ?, misi = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_lembaga, $jenis_lembaga, $nama_ketua, $periode, $visi, $misi, $id]);
        }
    } catch (PDOException $e) {
        // Error handling minimal sesuai template asli
    }

    header("Location: manage-lembaga.php");
    exit();
}

// --- Ambil semua data lembaga untuk tabel ---
try {
    // Ambil data tanpa kolom visi/misi untuk menjaga lebar tabel
    $sql_tabel = "SELECT id, nama_lembaga, jenis_lembaga, nama_ketua, periode, logo FROM lembaga_desa ORDER BY tanggal_dibuat DESC";
    $stmt_tabel = $pdo->query($sql_tabel);
    $lembaga_list = $stmt_tabel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Error handling minimal sesuai template asli
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Lembaga Desa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
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
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h1 { margin-bottom: 20px; }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input, select, textarea, button { /* Tambahkan textarea di sini */
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
        th { background: #34495e; color: white; }
        .aksi a {
            margin-right: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .aksi a.edit { color: #2980b9; }
        .aksi a.hapus { color: #c0392b; }
        /* CSS untuk Checkbox dan Preview Logo */
        .logo-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .logo-preview img {
            border: 1px solid #eee;
            border-radius: 4px;
        }
        .hapus-logo-checkbox {
            display: flex;
            align-items: center;
            margin-top: 5px;
            width: 100%;
        }
        .hapus-logo-checkbox input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
            margin-top: 0;
            vertical-align: middle; 
        }
        .hapus-logo-checkbox label {
            width: auto;
            margin-top: 0;
            cursor: pointer;
            vertical-align: middle;
        }
        .logo-preview p {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        /* Akhir CSS Checkbox dan Preview Logo */

        @media (max-width: 768px) {
            body { flex-direction: column; align-items: stretch; }
            table, thead, tbody, th, td, tr { display: block; }
            thead { display: none; }
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
                display: flex;
                gap: 6px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h1>Manajemen Lembaga Desa</h1>

        <div class="card">
            <h2><?php echo $edit_data ? 'Edit Lembaga Desa' : 'Tambah Lembaga Desa Baru'; ?></h2>
            <form action="manage-lembaga.php" method="POST" enctype="multipart/form-data">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                    <input type="hidden" name="logo_lama" value="<?php echo htmlspecialchars($edit_data['logo']); ?>">
                <?php endif; ?>

                <label>Nama Lembaga</label>
                <input type="text" name="nama_lembaga" value="<?php echo htmlspecialchars($edit_data['nama_lembaga'] ?? ''); ?>" required>

                <label>Jenis Lembaga</label>
                <select name="jenis_lembaga" required>
                    <option value="" disabled selected>Pilih Jenis Lembaga</option>
                    <?php
                    $daftar_jenis_lembaga = ["LPMD", "PKK", "RT/RW", "Karang Taruna"];
                    foreach ($daftar_jenis_lembaga as $jenis) {
                        $is_selected = ($edit_data && $edit_data['jenis_lembaga'] == $jenis) ? 'selected' : '';
                        
                        // Logika menonaktifkan jika sudah terpakai (hanya berlaku pada mode TAMBAH)
                        $is_disabled = (!$edit_data && in_array($jenis, $used_jenis_lembaga)) ? 'disabled' : '';
                        
                        // Jika mode EDIT, jenis lembaga yang sedang diedit tetap bisa dipilih
                        if ($edit_data && $edit_data['jenis_lembaga'] == $jenis) {
                            $is_disabled = '';
                        }
                        
                        echo "<option value=\"" . htmlspecialchars($jenis) . "\" $is_selected $is_disabled>" . htmlspecialchars($jenis) . "</option>";
                    }
                    ?>
                </select>

                <label>Nama Ketua</label>
                <input type="text" name="nama_ketua" value="<?php echo htmlspecialchars($edit_data['nama_ketua'] ?? ''); ?>" required>
                
                <label>Periode</label>
                <input type="text" name="periode" value="<?php echo htmlspecialchars($edit_data['periode'] ?? ''); ?>" placeholder="Contoh: 2023-2025" required>

                <label>Visi Lembaga</label>
                <textarea name="visi" rows="3" required><?php echo htmlspecialchars($edit_data['visi'] ?? ''); ?></textarea>

                <label>Misi Lembaga</label>
                <textarea name="misi" rows="5" required><?php echo htmlspecialchars($edit_data['misi'] ?? ''); ?></textarea>

                <label>Logo Lembaga (Opsional)</label>
                <input type="file" name="logo" accept="image/*">
                
                <?php if ($edit_data && $edit_data['logo']): ?>
                    <div class="logo-preview">
                        <p>Logo saat ini: <img src="uploads/<?php echo htmlspecialchars($edit_data['logo']); ?>" width="100"></p>
                    </div>
                    <div class="hapus-logo-checkbox">
                        <input type="checkbox" id="hapus_logo" name="hapus_logo" value="1">
                        <label for="hapus_logo">Hapus Logo Saat Ini</label>
                    </div>
                <?php endif; ?>

                <button type="submit" name="<?php echo $edit_data ? 'update' : 'tambah'; ?>">Simpan</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Lembaga Desa</h2>
            <p>**Catatan:** Untuk melihat Visi dan Misi, silakan klik tombol **Edit**.</p>
            <table>
                <thead>
                    <tr>
                        <th>Nama Lembaga</th>
                        <th>Jenis Lembaga</th>
                        <th>Nama Ketua</th>
                        <th>Periode</th>
                        <th>Logo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($lembaga_list) {
                    foreach ($lembaga_list as $row) {
                        echo "<tr>";
                        echo "<td data-label='Nama Lembaga'>" . htmlspecialchars($row['nama_lembaga']) . "</td>";
                        echo "<td data-label='Jenis Lembaga'>" . htmlspecialchars($row['jenis_lembaga']) . "</td>";
                        echo "<td data-label='Nama Ketua'>" . htmlspecialchars($row['nama_ketua']) . "</td>";
                        echo "<td data-label='Periode'>" . htmlspecialchars($row['periode']) . "</td>";
                        echo "<td data-label='Logo'>";
                        if ($row['logo']) {
                            echo "<img src='uploads/" . htmlspecialchars($row['logo']) . "' width='80'>";
                        }
                        echo "</td>";
                        echo "<td data-label='Aksi' class='aksi'>
                                <a href='manage-lembaga.php?edit=" . htmlspecialchars($row['id']) . "' class='edit'>Edit</a>
                                <a href='manage-lembaga.php?hapus=" . htmlspecialchars($row['id']) . "' class='hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data lembaga desa.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
require_once 'auth.php';
require_once 'config.php';

$jabatan_terisi = [];
try {
    $sql_terisi = "SELECT jabatan FROM perangkat_desa";
    $stmt_terisi = $pdo->query($sql_terisi);
    $jabatan_terisi = $stmt_terisi->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error saat mengambil jabatan terisi: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) mkdir($target_dir);
    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

    try {
        $sql = "INSERT INTO perangkat_desa (nama, jabatan, foto) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $jabatan, $foto]);
    } catch (PDOException $e) {
        die("Error saat menambah perangkat desa: " . $e->getMessage());
    }
    header("Location: manage-struktur.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    try {
        $sql_foto = "SELECT foto FROM perangkat_desa WHERE id = ?";
        $stmt_foto = $pdo->prepare($sql_foto);
        $stmt_foto->execute([$id]);
        $row_foto = $stmt_foto->fetch(PDO::FETCH_ASSOC);

        if ($row_foto && $row_foto['foto']) {
            $file_path = "uploads/" . $row_foto['foto'];
            if (file_exists($file_path)) unlink($file_path);
        }

        $sql = "DELETE FROM perangkat_desa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error saat menghapus perangkat desa: " . $e->getMessage());
    }
    header("Location: manage-struktur.php");
    exit();
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        $sql = "SELECT * FROM perangkat_desa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error saat mengambil data edit: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $foto_lama = $_POST['foto_lama'];
    $foto_baru = $_FILES['foto']['name'];

    try {
        if ($foto_baru) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

            if ($foto_lama && file_exists("uploads/" . $foto_lama)) unlink("uploads/" . $foto_lama);

            $sql = "UPDATE perangkat_desa SET nama = ?, jabatan = ?, foto = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $jabatan, $foto_baru, $id]);
        } else {
            $sql = "UPDATE perangkat_desa SET nama = ?, jabatan = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama, $jabatan, $id]);
        }
    } catch (PDOException $e) {
        die("Error saat update perangkat desa: " . $e->getMessage());
    }
    header("Location: manage-struktur.php");
    exit();
}

$perangkat_list = [];
try {
    $sql_tabel = "SELECT id, nama, jabatan, foto FROM perangkat_desa ORDER BY tanggal_dibuat DESC";
    $stmt_tabel = $pdo->query($sql_tabel);
    $perangkat_list = $stmt_tabel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error saat mengambil daftar perangkat desa: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Perangkat Desa</title>
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
input, select, button {
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
    td { border: none; padding: 6px 8px; }
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
    <h1>Manajemen Perangkat Desa</h1>
    <div class="card">
        <h2><?php echo $edit_data ? 'Edit Perangkat Desa' : 'Tambah Perangkat Desa Baru'; ?></h2>
        <form action="manage-struktur.php" method="POST" enctype="multipart/form-data">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($edit_data['foto']); ?>">
            <?php endif; ?>

            <label>Nama</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($edit_data['nama'] ?? ''); ?>" required>

            <label>Jabatan</label>
            <select name="jabatan" required>
                <option value="" disabled selected>Jabatan</option>
                <?php
                $daftar_jabatan = [
                    "Kepala Desa","Sekretaris Desa","Kasi Kesejahteraan","Kasi Pelayanan","Kasi Pemerintahan",
                    "Kaur Keuangan","Kaur Perencanaan","Kaur Tata Usaha dan Umum","Kadus A","Kadus B","Kadus C","Kadus D",
                    "Badan Permusyawaratan Desa"
                ];
                foreach ($daftar_jabatan as $jabatan) {
                    $is_selected = ($edit_data && $edit_data['jabatan'] == $jabatan) ? 'selected' : '';
                    $is_disabled = (in_array($jabatan, $jabatan_terisi) && $jabatan != ($edit_data['jabatan'] ?? '')) ? 'disabled' : '';
                    echo "<option value=\"" . htmlspecialchars($jabatan) . "\" $is_selected $is_disabled>" . htmlspecialchars($jabatan) . "</option>";
                }
                ?>
            </select>

            <label>Foto</label>
            <input type="file" name="foto" accept="image/*">
            <?php if ($edit_data && $edit_data['foto']): ?>
                <p>Foto saat ini: <img src="uploads/<?php echo htmlspecialchars($edit_data['foto']); ?>" width="100"></p>
            <?php endif; ?>

            <button type="submit" name="<?php echo $edit_data ? 'update' : 'tambah'; ?>">Simpan</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Perangkat Desa</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($perangkat_list) {
                foreach ($perangkat_list as $row) {
                    echo "<tr>";
                    echo "<td data-label='Nama'>" . htmlspecialchars($row['nama']) . "</td>";
                    echo "<td data-label='Jabatan'>" . htmlspecialchars($row['jabatan']) . "</td>";
                    echo "<td data-label='Foto'>";
                    if ($row['foto']) echo "<img src='uploads/" . htmlspecialchars($row['foto']) . "' width='80'>";
                    echo "</td>";
                    echo "<td data-label='Aksi' class='aksi'>
                            <a href='manage-struktur.php?edit=" . htmlspecialchars($row['id']) . "' class='edit'>Edit</a>
                            <a href='manage-struktur.php?hapus=" . htmlspecialchars($row['id']) . "' class='hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data perangkat desa.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

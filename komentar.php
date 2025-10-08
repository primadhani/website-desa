<?php
// Panggil file konfigurasi database untuk koneksi PDO
require_once 'config.php';

// Inisialisasi variabel untuk ID berita, pastikan diambil dari GET
$id_berita = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Proses pengiriman formulir jika tombol 'KIRIM' ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nomor_hp = $_POST['nomor_hp'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Validasi sederhana
    if (!empty($nama) && !empty($nomor_hp) && !empty($pesan) && $id_berita > 0) {
        try {
            $sql = "INSERT INTO komentar (id_berita, nama, nomor_hp, email, pesan) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_berita, $nama, $nomor_hp, $email, $pesan]);

            echo "<script>alert('Komentar berhasil dikirim!'); window.location.href='detail-berita.php?id=" . $id_berita . "';</script>";
            
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Nama, Nomor HP, dan Pesan harus diisi.');</script>";
    }
}

// Ambil semua komentar untuk berita ini dari database
$result_komentar = []; // Inisialisasi array kosong
if ($id_berita > 0) {
    try {
        $sql_komentar = "SELECT nama, pesan, tanggal_kirim FROM komentar WHERE id_berita = ? ORDER BY tanggal_kirim DESC";
        $stmt_komentar = $pdo->prepare($sql_komentar);
        $stmt_komentar->execute([$id_berita]);
        $result_komentar = $stmt_komentar->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}
?>

<div class="komentar-section">
    <h2>Tulis Komentar</h2>
    <div class="komentar-form-container">
        <form action="detail-berita.php?id=<?php echo $id_berita; ?>" method="POST" class="komentar-form">
            <div class="form-group-inline">
                <div class="input-container">
                    <label for="nama"><i class="fas fa-user"></i></label>
                    <input type="text" id="nama" name="nama" placeholder="Nama Anda*" required>
                </div>
                <div class="input-container">
                    <label for="nomor_hp"><i class="fas fa-phone"></i></label>
                    <input type="tel" id="nomor_hp" name="nomor_hp" placeholder="Nomor Hp Anda*" required>
                </div>
            </div>
            <div class="form-group-full">
                <div class="input-container">
                    <label for="email"><i class="fas fa-envelope"></i></label>
                    <input type="email" id="email" name="email" placeholder="Alamat Email Anda">
                </div>
            </div>
            <div class="form-group-full">
                <div class="input-container">
                    <textarea id="pesan" name="pesan" rows="5" placeholder="Tulis Pesan Anda*" required></textarea>
                </div>
            </div>

            <div class="form-group-submit">
                <button type="submit" name="kirim" class="btn-kirim">KIRIM</button>
            </div>
        </form>
    </div>

    <div class="komentar-list">
        <h3>Komentar Lainnya</h3>
        <?php if (!empty($result_komentar)) {
            foreach($result_komentar as $row) { ?>
                <div class="komentar-item">
                    <div class="komentar-header">
                        <i class="fas fa-user-circle komentar-avatar"></i>
                        <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                        <span class="komentar-date"><?php echo date('d M Y, H:i', strtotime($row['tanggal_kirim'])); ?></span>
                    </div>
                    <p class="komentar-text"><?php echo nl2br(htmlspecialchars($row['pesan'])); ?></p>
                </div>
            <?php }
        } else {
            echo "<p>Belum ada komentar.</p>";
        } ?>
    </div>
</div>

<style>
/* CSS untuk bagian komentar */
.komentar-section {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.komentar-section h2 {
    font-size: 1.5em;
    color: #333;
    margin-bottom: 20px;
}

.komentar-form-container {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
}

.komentar-form .form-group-inline {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.komentar-form .form-group-full {
    margin-bottom: 15px;
}

.komentar-form .input-container {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
}

.komentar-form .input-container label {
    position: absolute;
    left: 15px;
    color: #999;
}

.komentar-form input,
.komentar-form textarea {
    width: 100%;
    padding: 10px 10px 10px 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

.komentar-form textarea {
    padding-top: 15px;
    height: 120px;
}

.komentar-form .btn-kirim {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s;
}

.komentar-form .btn-kirim:hover {
    background-color: #218838;
}

.komentar-list {
    margin-top: 30px;
}

.komentar-list h3 {
    font-size: 1.3em;
    color: #555;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.komentar-item {
    background: #f1f1f1;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.komentar-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.komentar-avatar {
    font-size: 2em;
    color: #999;
    margin-right: 10px;
}

.komentar-header strong {
    font-size: 1.1em;
    color: #333;
}

.komentar-date {
    font-size: 0.8em;
    color: #888;
    margin-left: auto;
}

.komentar-text {
    margin: 0;
    line-height: 1.6;
    color: #555;
}

@media (max-width: 600px) {
    .komentar-form .form-group-inline {
        flex-direction: column;
        gap: 0;
    }

    h1 { font-size: 24px; line-height: 1.2; }
    h2 { font-size: 22px; line-height: 1.2; }
    h3 { font-size: 20px; line-height: 1.3; }
    h4 { font-size: 18px; line-height: 1.4; }
    h5 { font-size: 16px; line-height: 1.4; }
    h6 { font-size: 14px; line-height: 1.5; }
    p { font-size: 14px; line-height: 1.5; }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
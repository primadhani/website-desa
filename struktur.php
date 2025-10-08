<?php
require_once 'config.php';

$sql = "SELECT id, nama, jabatan, foto 
        FROM perangkat_desa 
        ORDER BY FIELD(jabatan, 
            'Kepala Desa',
            'Sekretaris',
            'Kepala Seksi Pemerintahan',
            'Pelaksana Seksi Pemerintahan',
            'Kepala Seksi Kesejahteraan & Pelayanan',
            'Kepala Urusan Umum dan Pemerintahan',
            'Pelaksana Urusan Umum dan Pemerintahan',
            'Kepala Urusan Keuangan',
            'Pelaksana Urusan Keuangan',
            'Kepala Dusun Selo Kidul',
            'Kepala Dusun Selo Lor',
            'Kepala Dusun Selo Kulon',
            'Kepala Dusun Ngrombot',
            'Kepala Dusun Prayungan'
        )";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Perangkat Desa</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.judul {
    text-align: center;
    padding: 40px 20px 20px;
    margin: 0;
    color: #5a1212;
}

.struktur-image {
    display: block;
    max-width: 90%;
    height: auto;
    margin: 20px auto;
    border: 1px solid #ddd;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.struktur-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 20px;
}

.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease-in-out;
}

.photo-container {
    width: 100%;
    aspect-ratio: 4 / 3;
    position: relative;
    background-color: #e0e0e0;
}

.photo-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.info-container {
    padding: 15px;
    text-align: center;
    color: #fff;
    background-color: #5a1212;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-grow: 1;
}

.card-no-photo .info-container {
    min-height: 150px;
    border-radius: 10px;
}

.info-container h3 {
    font-size: 1.1em;
    font-weight: 700;
    margin-bottom: 5px;
    line-height: 1.3;
}

.info-container p {
    font-size: 0.85em;
    font-weight: 400;
    margin: 0;
}

@media (min-width: 600px) {
    .struktur-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .struktur-container {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (max-width: 600px) {
    h1 {
        font-size: 22px;
        padding: 15px 10px 10px;
    }

    .info-container h3 {
        font-size: 0.95em;
    }

    .info-container p {
        font-size: 0.75em;
    }

    .struktur-container {
        gap: 15px;
        padding: 0 10px;
    }

    .card {
        border-radius: 8px;
    }

    .photo-container {
        aspect-ratio: 1 / 1;
    }
}
</style>

</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="judul">
        <h1>Struktur Pengurus Desa Selorejo</h1>
    </div>

    <img src="aset/struktur.png" alt="Struktur Organisasi Desa Selorejo" class="struktur-image">

    <div class="struktur-container">
        <?php if (!empty($result)): ?>
            <?php foreach ($result as $row): ?>
                <?php 
                    $has_photo = !empty($row['foto']);
                    $card_class = $has_photo ? 'card' : 'card card-no-photo';
                ?>
                <div class="<?php echo $card_class; ?>">
                    <?php if ($has_photo): ?>
                        <div class="photo-container">
                            <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="info-container">
                        <h3><?php echo htmlspecialchars(strtoupper($row['nama'])); ?></h3>
                        <p><?php echo htmlspecialchars($row['jabatan']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1;">Tidak ada data perangkat desa yang ditemukan.</p>
        <?php endif; ?>
    </div>      
    <?php include 'footer.php'; ?>
</body>
</html>
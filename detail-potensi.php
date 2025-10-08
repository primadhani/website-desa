<?php
require_once 'config.php';

$judul = "Akses tidak valid";
$isi_potensi = "Halaman ini memerlukan ID potensi yang valid untuk ditampilkan.";
$foto = null;
$tanggal_dibuat = null;
$potensi_ditemukan = false;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_potensi = $_GET['id'];

    try {
        $sql = "SELECT id, judul, isi, foto, tanggal_dibuat FROM potensi WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_potensi]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $judul = htmlspecialchars($row['judul']);
            $isi_potensi = htmlspecialchars($row['isi']);
            $foto = htmlspecialchars($row['foto']);
            $tanggal_dibuat = htmlspecialchars($row['tanggal_dibuat']);
            $potensi_ditemukan = true;
        } else {
            $judul = "Potensi tidak ditemukan";
            $isi_potensi = "Maaf, potensi yang Anda cari tidak tersedia.";
        }
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $judul; ?> - Potensi Desa</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .page-content-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .main-column {
            flex: 2;
        }
        
        .aside-column {
            flex: 1;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .potensi-detail-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .potensi-detail-content h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .potensi-detail-content .potensi-date {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 20px;
            display: block;
        }

        .potensi-detail-content p {
            font-size: 1em;
            line-height: 1.6;
            color: #555;
            text-align: justify;
        }
        
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #5a1212;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .page-content-wrapper {
                flex-direction: column;
                margin: 20px auto;
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
</head>
<body>
    <?php include 'nav.php'; ?>
    
    <div class="page-content-wrapper">
        <div class="main-column">
            <div class="container">
                <?php if ($potensi_ditemukan): ?>
                    <div class="potensi-detail-content">
                        <h1><?php echo $judul; ?></h1>
                        <span class="potensi-date">
                            <?php
                            $date = new DateTime($tanggal_dibuat);
                            echo $date->format('l, F j, Y');
                            ?>
                        </span>
                        <?php if ($foto): ?>
                            <img src="uploads/<?php echo $foto; ?>" alt="<?php echo $judul; ?>" class="potensi-detail-image">
                        <?php endif; ?>
                        <p><?php echo nl2br($isi_potensi); ?></p>
                        <a href="profil-desa.php" class="back-button">Kembali</a>
                    </div>
                <?php else: ?>
                    <div class="potensi-detail-content" style="text-align: center;">
                        <h1><?php echo $judul; ?></h1>
                        <p><?php echo $isi_potensi; ?></p>
                        <a href="profil-desa.php" class="back-button"> Kembali</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="aside-column">
            <?php include 'aside.php'; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
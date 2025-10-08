<?php
require_once 'config.php';

$judul = "Akses tidak valid";
$isi_berita = "Halaman ini memerlukan ID berita yang valid untuk ditampilkan.";
$gambar = null;
$tanggal_dibuat = null;
$berita_ditemukan = false;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_berita = $_GET['id'];

    try {
        $sql = "SELECT id, judul, isi_berita, gambar, tanggal_dibuat FROM berita WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_berita]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $judul = htmlspecialchars($row['judul']);
            $isi_berita = htmlspecialchars($row['isi_berita']);
            $gambar = htmlspecialchars($row['gambar']);
            $tanggal_dibuat = htmlspecialchars($row['tanggal_dibuat']);
            $berita_ditemukan = true;
        } else {
            $judul = "Berita tidak ditemukan";
            $isi_berita = "Maaf, berita yang Anda cari tidak tersedia.";
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
    <title><?php echo $judul; ?> - Berita Desa</title>
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
        
        .news-detail-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .news-detail-content h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .news-detail-content .news-date {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 20px;
            display: block;
        }

        .news-detail-content p {
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
                <?php if ($berita_ditemukan): ?>
                    <div class="news-detail-content">
                        <h1><?php echo $judul; ?></h1>
                        <span class="news-date">
                            <?php
                            $date = new DateTime($tanggal_dibuat);
                            echo $date->format('l, F j, Y');
                            ?>
                        </span>
                        <?php if ($gambar): ?>
                            <img src="uploads/<?php echo $gambar; ?>" alt="<?php echo $judul; ?>" class="news-detail-image">
                        <?php endif; ?>
                        <p><?php echo nl2br($isi_berita); ?></p>
                        <a href="berita.php" class="back-button">Kembali ke Berita</a>
                    </div>
                <?php else: ?>
                    <div class="news-detail-content" style="text-align: center;">
                        <h1><?php echo $judul; ?></h1>
                        <p><?php echo $isi_berita; ?></p>
                        <a href="berita.php" class="back-button">Kembali ke Berita</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php include 'komentar.php'; ?>
        </div>

        <div class="aside-column">
            <?php include 'aside.php'; ?>
        </div>
    </div>
      <?php include 'footer.php'; ?>

</body>
</html>
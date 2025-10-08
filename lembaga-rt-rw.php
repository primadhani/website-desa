<?php
require_once 'config.php';

$sql = "SELECT nama_lembaga, jenis_lembaga, nama_ketua, logo, periode 
        FROM lembaga_desa 
        WHERE jenis_lembaga = 'RT/RW' 
        LIMIT 1";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $lembaga_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembaga RT/RW</title>
    <link rel="icon" href="aset/favicon.png" type="image/png">

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #333;
        }

        .page-wrapper {
            display: flex;
            flex-grow: 1;
            margin: 20px auto;
            max-width: 1200px;
            width: 100%;
            gap: 20px;
            padding: 0 20px;
        }

        .sidebar {
            width: 250px;
            background-color: #e9ecef;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .main-content {
            flex-grow: 1;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }

        h1, h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .visi-misi {
            background-color: #e9ecef;
            padding: 15px;
            border-left: 5px solid #5a1212;
            border-radius: 6px;
        }

        .visi-misi h3 {
            margin-top: 0;
            color: #5a1212;
        }

        .visi-misi ol {
            padding-left: 20px;
        }

        @media (max-width: 1024px) {
            .page-wrapper {
                max-width: 95%;
                padding: 0 10px;
            }

            .sidebar {
                width: 220px;
                padding: 15px;
            }

            .main-content {
                padding: 20px;
            }

            .header img {
                max-width: 120px;
            }
        }

        @media (max-width: 768px) {
            .page-wrapper {
                flex-direction: column;
                margin: 10px auto;
                padding: 0 10px;
            }

            .main-content, .sidebar {
                width: 100%;
                margin: 0;
                padding: 15px;
            }

            .header img {
                max-width: 100px;
            }

            h1 {
                font-size: 1.4em;
            }

            h2 {
                font-size: 1.1em;
            }

            p, li {
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }

            h1 {
                font-size: 1.2em;
            }

            h2 {
                font-size: 1em;
            }

            p, li {
                font-size: 0.85em;
            }

            .header img {
                max-width: 80px;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="page-wrapper">
        <div class="main-content">
            <header class="header">
                <?php if ($lembaga_data && !empty($lembaga_data['logo'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($lembaga_data['logo']); ?>" alt="Logo <?php echo htmlspecialchars($lembaga_data['nama_lembaga'] ?? 'RT/RW'); ?>">
                <?php endif; ?>
                <h1><?php echo $lembaga_data ? htmlspecialchars($lembaga_data['nama_lembaga']) : 'Lembaga RT/RW'; ?></h1>
            </header>
            <hr>
            <?php if ($lembaga_data): ?>
            <div class="section">
                <h2>Tentang Lembaga</h2>
                <ul>
                    <li><strong>Jenis Lembaga:</strong> <?php echo htmlspecialchars($lembaga_data['jenis_lembaga']); ?></li>
                    <li><strong>Nama Ketua:</strong> <?php echo htmlspecialchars($lembaga_data['nama_ketua']); ?></li>
                    <li><strong>Periode:</strong> <?php echo htmlspecialchars($lembaga_data['periode'] ?? '-'); ?></li>
                </ul>
            </div>
            <div class="section visi-misi">
                <h2>Visi dan Misi</h2>
                <h3>Visi</h3>
                <p>Terwujudnya lingkungan Rukun Tetangga dan Rukun Warga yang harmonis, aman, bersih, dan sejahtera melalui gotong royong dan pelayanan yang prima.</p>
                <h3>Misi</h3>
                <ol>
                    <li>Meningkatkan partisipasi aktif warga dalam setiap kegiatan sosial kemasyarakatan.</li>
                    <li>Menciptakan keamanan dan ketertiban di lingkungan sekitar.</li>
                    <li>Menjadi jembatan komunikasi yang efektif antara warga dan pemerintah desa.</li>
                    <li>Memberikan pelayanan administratif yang cepat, transparan, dan akuntabel kepada warga.</li>
                </ol>
            </div>
            <?php else: ?>
            <div class="section">
                <p>Data lembaga RT/RW tidak ditemukan.</p>
            </div>
            <?php endif; ?>
        </div>
        <aside class="sidebar">
            <?php include 'aside.php'; ?>
        </aside>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

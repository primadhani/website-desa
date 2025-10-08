<?php
require_once 'config.php';

$sql_perangkat_desa = "
    SELECT id, nama, jabatan, foto 
    FROM perangkat_desa 
    ORDER BY FIELD(
        jabatan, 
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

    )
    LIMIT 3
";

try {
    $stmt_perangkat_desa = $pdo->prepare($sql_perangkat_desa);
    $stmt_perangkat_desa->execute();
    $result_perangkat_desa = $stmt_perangkat_desa->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query perangkat desa gagal: " . $e->getMessage());
}

$sql_berita = "
    SELECT id, judul, isi_berita, gambar, tanggal_dibuat 
    FROM berita 
    ORDER BY tanggal_dibuat DESC 
    LIMIT 6
";

try {
    $stmt_berita = $pdo->prepare($sql_berita);
    $stmt_berita->execute();
    $result_berita = $stmt_berita->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query berita gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="description" content="Website resmi Desa Selorejo, Kecamatan Bagor, Kabupaten Nganjuk. Temukan informasi profil desa, sejarah, berita terbaru, potensi, dan layanan masyarakat.">
    <title>Desa Selorejo Bagor Nganjuk</title>
    <link rel="canonical" href="https://selorejo.my.id/" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .home .hero-section {
            position: relative;
            background-image: url('aset/home.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding-top: 56px;
        }
        .home .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .home .hero-content {
            position: relative;
            z-index: 2;
        }
        .home .hero-title {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .home .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
        }
        .home .announcement {
            font-style: italic;
            margin-top: 1rem;
        }

        .section-title {
            text-align: center;
            padding: 40px 0 20px;
            color: #333;
        }

        .berita-container, .struktur-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .berita-card, .card {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        .berita-card:hover, .card:hover {
            transform: translateY(-5px);
        }

        .berita-card .image-container {
            width: 100%;
            height: 200px;
        }
        .berita-card .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .berita-card .content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .berita-card .content h3 {
            font-size: 1.1em;
            margin: 0 0 10px;
            color: #333;
        }
        .berita-card .content p {
            font-size: 0.9em;
            color: #666;
            line-height: 1.4;
            flex-grow: 1;
        }
        .berita-card .content .read-more {
            margin-top: auto;
            color: #5a1212;
            text-decoration: none;
            font-weight: bold;
        }
        .berita-card .content .read-more:hover {
            text-decoration: underline;
        }

        .photo-container {
            width: 100%;
            padding-bottom: 100%; 
            position: relative;
            background-color: #eee;
        }
        .photo-container img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-container {
            padding: 15px;
            text-align: center;
            background-color: #fff;
        }
        .info-container h3 {
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #5a1212;
        }
        .info-container p {
            font-size: 0.9em;
            margin: 0;
            color: #555;
        }
                
        .card-no-photo {
            min-height: 150px; 
            justify-content: center; 
            padding: 10px; 
            background-color: transparent !important; 
            box-shadow: none !important;
        }

        .card-no-photo .info-container {
            padding: 25px 15px; 
            background-color: #5a1212;
            color: #fff;
            border: none;
            border-radius: 8px; 
            min-height: 100px; 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-no-photo .info-container h3,
        .card-no-photo .info-container p {
            color: #fff !important;
        }

        .read-more-button {
            text-align: center;
            margin-bottom: 40px;
        }
        .read-more-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #5a1212;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .read-more-button a:hover {
            background-color: #5a1212;
        }
        .map-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .map-subtitle {
            text-align: center;
            font-size: 1.1em;
            color: #555;
            margin-top: -15px;
            margin-bottom: 20px;
        }
        #map iframe {
            width: 100%;
            height: 450px;
            border-radius: 10px;
            border: none;
        }
        @media (max-width: 1024px) {
            .struktur-container, .berita-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .struktur-container, .berita-container {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 24px;
                line-height: 1.2;
            }
            h2 {
                font-size: 22px;
                line-height: 1.2;
            }
            h3 {
                font-size: 20px;
                line-height: 1.3;
            }
            h4 {
                font-size: 18px;
                line-height: 1.4;
            }
            h5 {
                font-size: 16px;
                line-height: 1.4;
            }
            h6 {
                font-size: 14px;
                line-height: 1.5;
            }
            p {
                font-size: 14px;
                line-height: 1.5;
            }
        }

        .aside-column {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .aside-kiri {
            flex: 3;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .aside-kanan {
            flex: 1;
            background: #ffffff;
            padding: 15px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .aside-column {
                flex-direction: column;
            }
            .aside-kiri, .aside-kanan {
                flex: 100%;
            }
        }

        .sambutan {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-top: 0px;
        }
        .sambutan h2 {
            margin-bottom: 15px;
            color: #333;
        }
        .sambutan p {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #555;
            text-align: justify;
        }
        .sambutan .penulis {
            margin-top: 20px;
            text-align: right;
            font-size: 0.95em;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="home">
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Selamat Datang</h1>
                <h1 class="hero-title">Website Resmi Desa Selorejo</h1>
                <p class="hero-subtitle">Sumber informasi terbaru tentang pemerintahan di Desa Selorejo, Bagor, Nganjuk</p>
                <p class="announcement">Bisa ditambah kata kata, atau juga dihapus</p>
            </div>
        </section>
    </div>

    <hr>

    <div class="container">
        <h2 class="section-title">Struktur Pengurus Desa Selorejo</h2>
        <div class="struktur-container">
            <?php if (!empty($result_perangkat_desa)): ?>
                <?php foreach ($result_perangkat_desa as $row): ?>
                    <?php 
                        $has_photo = !empty($row['foto']);
                    ?>
                    
                    <div class="card <?php echo $has_photo ? '' : 'card-no-photo'; ?>"> 
                        
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
                <p>Tidak ada data perangkat desa yang ditemukan.</p>
            <?php endif; ?>
        </div>
        <div class="read-more-button">
            <a href="struktur.php">Lihat Semua Struktur</a>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Berita Terbaru</h2>
        <div class="berita-container">
            <?php if (!empty($result_berita)): ?>
                <?php foreach ($result_berita as $row): ?>
                    <div class="berita-card" onclick="window.location.href='berita_detail.php?id=<?php echo $row['id']; ?>'">
                        <?php if (!empty($row['gambar'])): ?>
                            <div class="image-container">
                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Berita">
                            </div>
                        <?php endif; ?>
                        <div class="content">
                            <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($row['isi_berita'], 0, 100)) . '...'; ?></p>
                            <a href="detail-berita.php?id=<?php echo $row['id']; ?>" class="read-more">Baca Selengkapnya</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada berita yang ditemukan.</p>
            <?php endif; ?>
        </div>
        <div class="read-more-button">
            <a href="berita.php">Lihat Semua Berita</a>
        </div>
    </div>

    <div class="map-container">
        <h2 class="section-title">PETA DESA</h2>
        <p class="map-subtitle">Menampilkan Peta Desa Dengan <i>Interest Point</i> Desa Selorejo</p>
        <div id="map">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13698.027674435898!2d111.85389773691743!3d-7.583766608742392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784ace60239ad5%3A0x3bf4855a64a6b84d!2sSelorejo%2C%20Kec.%20Bagor%2C%20Kabupaten%20Nganjuk%2C%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1758546145338!5m2!1sid!2sid" 
                width="100%" 
                height="450px" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <div class="aside-column container">
        <div class="aside-kiri">
            <div class="sambutan">
                <h2>Sambutan Kepala Desa Selorejo</h2>
                <p>
                    Assalamu’alaikum Warahmatullahi Wabarakatuh,
                </p>
                <p>
                    Puji syukur kita panjatkan ke hadirat Allah SWT, karena atas rahmat 
                    dan karunia-Nya, Website Resmi Desa Selorejo ini dapat hadir 
                    sebagai sarana informasi, komunikasi, dan transparansi pemerintahan desa.
                    Website ini diharapkan dapat menjadi jendela informasi yang memberikan 
                    kemudahan bagi masyarakat dalam mengakses berita terbaru, program kerja, 
                    kegiatan desa, serta layanan administrasi yang tersedia.
                </p>
                <p>
                    Kami berkomitmen untuk selalu meningkatkan kualitas pelayanan publik 
                    serta membuka ruang partisipasi masyarakat dalam pembangunan desa. 
                    Dengan adanya website ini, kami berharap seluruh elemen masyarakat 
                    Desa Selorejo maupun pihak luar desa dapat memperoleh informasi yang 
                    akurat, cepat, dan terpercaya.
                </p>
                <p>
                    Semoga dengan adanya media informasi ini, terjalin komunikasi yang lebih 
                    baik antara pemerintah desa dengan masyarakat, sehingga mampu menciptakan 
                    desa yang maju, mandiri, dan sejahtera.
                </p>
                <p>
                    Wassalamu’alaikum Warahmatullahi Wabarakatuh.
                </p>

                <div class="penulis">
                    <strong>Hormat Kami,</strong><br>
                    Kepala Desa Selorejo<br>
                </div>
            </div>
        </div>
        <div class="aside-kanan">
            <?php include 'aside.php'; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
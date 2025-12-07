<?php
require_once 'config.php';


try {
    $sql = "SELECT id, judul, isi, foto, tanggal_dibuat FROM potensi ORDER BY tanggal_dibuat DESC LIMIT 6";
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
    <title>Document</title>
</head>
<style>
.sejarah-container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.sejarah-title {
    color: #5a1212;
    font-size: 2.5em;
    font-weight: 700;
    margin-bottom: 20px;
}

.sejarah-content {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.sejarah-text {
    flex: 1;
    line-height: 1.8;
    color: #555;
    font-size: 1em;
}

.sejarah-image {
    flex-shrink: 0;
    width: 400px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    order: 2;
}

.sejarah-image img {
    width: 100%;
    height: auto;
    display: block;
}

.visi-misi-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
}

.visi-card,
.misi-card {
    background-color: #fff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.visi-card h2,
.misi-card h2 {
    color: #5a1212;
    font-size: 2.5em;
    font-weight: 700;
    margin-bottom: 20px;
}

.visi-card p {
    font-size: 1.1em;
    line-height: 1.6;
    color: #555;
    font-weight: 500;
}

.misi-card ol {
    list-style-type: none;
    counter-reset: my-awesome-counter;
    text-align: left;
    padding-left: 0;
}

.misi-card li {
    font-size: 1em;
    line-height: 1.8;
    color: #555;
    font-weight: 500;
    margin-bottom: 10px;
    position: relative;
    padding-left: 30px;
}

.misi-card li:before {
    content: counter(my-awesome-counter);
    counter-increment: my-awesome-counter;
    position: absolute;
    left: 0;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background-color: #5a1212;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;
    font-size: 0.9em;
}

.lokasi-title {
    color: #5a1212;
    font-size: 2.5em;
    font-weight: 700;
    margin: 50px auto 20px 100px;
}

.lokasi-container {
    display: flex;
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto 50px auto;
    padding: 20px;
}

.lokasi-info-card,
.lokasi-peta-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    color: #333;
    flex: 1;
}

.lokasi-info-card h1 {
    font-size: 1.5em;
    font-weight: 600;
    margin-bottom: 20px;
}

.batas-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.batas-grid p {
    margin: 0;
    line-height: 1.5;
}

.batas-grid p strong {
    font-size: 1.2em;
}

.info-lainnya {
    margin-top: 30px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.2em;
    margin-bottom: 15px;
}

.info-item p {
    margin: 0;
}

.info-item strong {
    font-weight: 600;
}

.info-lainnya hr {
    border: none;
    height: 1px;
    background-color: #eee;
    margin: 20px 0;
}

.lokasi-peta-card {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.potensi {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.potensi-card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.potensi-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}

.potensi-content {
    padding: 25px;
}

.potensi-content h3 {
    margin-top: 0;
    color: #1f364d;
    font-weight: 600;
}

.potensi-content p {
    color: #555;
    font-size: 0.95rem;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #5a1212;
    color: #fff;
    text-decoration: none;
    border-radius: 50px;
    transition: background-color 0.3s;
    font-weight: 600;
}

.btn:hover {
    background-color: #5a1212;
}

.potensi-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.potensi-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 0 10px;
}

.potensi-header h1{
    color: #5a1212;
}

.potensi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.potensi-date {
    font-size: 0.8em;
    color: #888;
    margin-bottom: 10px;
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

@media (max-width: 768px) {
    .sejarah-container {
        padding: 20px;
    }
    
    .sejarah-content {
        flex-direction: column;
    }

    .sejarah-text {
        order: 2;
        width: 100%;
    }

    .sejarah-image {
        width: 100%;
        order: 1;
        margin-bottom: 20px;
    }

    .visi-misi-container {
        padding: 10px;
        margin: 30px auto;
    }

    .visi-card,
    .misi-card {
        padding: 20px;
    }
    
    .visi-card h2,
    .misi-card h2 {
        font-size: 2em;
    }
    
    .visi-card p,
    .misi-card li {
        font-size: 0.9em;
    }

    .lokasi-container {
        flex-direction: column;
        padding: 10px;
    }

    .lokasi-title {
        margin: 30px auto 20px 20px;
        font-size: 2em;
    }

    .lokasi-peta-card {
        min-height: 250px;
        padding: 15px;
    }

    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }

    .potensi-grid {
        grid-template-columns: 1fr;
    }

    .potensi-card img {
        height: 180px;
    }

    .potensi-content h3 {
        font-size: 1em;
    }

    .potensi-content p {
        font-size: 0.85em;
    }

    .container {
        padding: 10px;
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

@media (max-width: 480px) {
    .lokasi-peta-card {
        min-height: 200px;
        padding: 10px;
    }

    .potensi-card img {
        height: 150px;
    }

    .potensi-content h3 {
        font-size: 0.9em;
    }

    .potensi-content p {
        font-size: 0.8em;
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

</style>
<body>
    <?php include 'nav.php'; ?>

<div class="sejarah-container">
    <h1 class="sejarah-title">Sejarah Desa Selorejo</h1>
    <div class="sejarah-content">
        <div class="sejarah-text">
            <p>Desa Selorejo berdiri sejak zaman kolonial Belanda, sekitar awal abad ke-20. Nama Selorejo berasal dari dua kata dalam bahasa Jawa, yaitu “selo” yang berarti batu, dan “rejo” yang berarti ramai atau makmur. Nama ini mencerminkan keadaan alam desa yang dipenuhi bebatuan alam di sekitar sungai, sekaligus harapan agar desa selalu ramai penduduk dan sejahtera.

Menurut cerita para sesepuh, penduduk pertama Desa Selorejo adalah sekelompok petani dan penggembala yang berasal dari daerah pegunungan sekitarnya. Mereka memilih menetap di wilayah ini karena tanahnya subur, terdapat sumber air dari sungai kecil yang jernih, serta hutan yang menyediakan kayu dan hasil alam. Lambat laun, perkampungan kecil itu berkembang menjadi sebuah desa dengan sistem sosial sederhana yang dipimpin oleh seorang bekel (kepala desa pada masa itu).

Pada masa penjajahan Belanda, Desa Selorejo menjadi salah satu daerah penyuplai hasil bumi seperti padi, jagung, dan ketela. Beberapa warga juga terlibat dalam perjuangan kemerdekaan dengan menjadi kurir atau penyedia logistik bagi para pejuang. Cerita perjuangan ini masih dikenang melalui kisah-kisah lisan yang dituturkan turun-temurun.

Setelah Indonesia merdeka, Desa Selorejo mengalami perkembangan pesat. Pemerintah mulai membangun jalan penghubung antar-desa, sekolah dasar, serta pasar kecil yang menjadi pusat kegiatan ekonomi masyarakat. Pertanian tetap menjadi mata pencaharian utama, namun seiring waktu, masyarakat juga mulai menekuni bidang lain seperti perdagangan, kerajinan, dan jasa.

Kini, Desa Selorejo telah berubah menjadi desa yang modern namun tetap mempertahankan nilai-nilai tradisi. Gotong royong, kenduri desa, dan upacara adat masih dilestarikan, bersanding dengan teknologi dan pembangunan infrastruktur baru. Desa ini menjadi bukti perjalanan panjang masyarakat dari masa lalu yang sederhana menuju kehidupan yang lebih maju tanpa melupakan akar budaya.</p>
        </div>
        <div class="sejarah-image">
            <img src="aset/logo.png" alt="Pemandangan Desa Tamang">
        </div>
    </div>
</div>
        
    <div class="visi-misi-container">
        <div class="visi-card">
        <h2>Visi</h2>
        <p>MENJADIKAN DESA SELOREJO YANG MANDIRI, SEJAHTERA, BERMARTABAT, BERKUALITAS, TRANSPARAN GUNA TERWUJUDNYA PROGRAM MASYARAKAT YANG ADIL, MAKMUR, SEJAHTERA DAN BERDAYA SAING SERTA HARMONIS DALAM KEHIDUPAN BERMASYARAKAT.</p>
    </div>
    
    <div class="misi-card">
        <h2>Misi</h2>
        <ol>
            <li>Memaksimalkan sistem kerja Aparatur Pemerintah Desa Sesuai Tugas, Fungsi dan Wewenang</li>
            <li>Menyelenggarakan Program Desa Berdasarkan Musyawarah Yang Termuat Dalam RPJMDesa, RKPDes dan APBDesa.</li>
            <li>Melaksanakan Program Desa Dengan Transparan Terbuka dan Bertanggungjawab.</li>
            <li>Meningkatkan Pelayanan Kepada Masyarakat dari Yang Tidak Tau Sampai Mengerti dan Dari Yang Tidak Mampu Menjadi Mandiri Dengan Baik.</li>
        </ol>
    </div>
</div>  

<div class="potensi-container">
    <div class="potensi-header">
        <h1>Potensi Desa</h1>
    </div>

    <div class="potensi-grid">
        <?php
        if ($result && count($result) > 0) {
            foreach($result as $row) {
                ?>
                <div class="potensi-card">
                    <?php if ($row['foto']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                    <?php endif; ?>
                    <div class="potensi-content">
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p class="potensi-date">
                            <?php
                            $date = new DateTime($row['tanggal_dibuat']);
                            echo $date->format('l, F j, Y');
                            ?>
                        </p>
                        <p><?php echo substr(htmlspecialchars($row['isi']), 0, 150) . '...'; ?></p>
                        <a href="detail-potensi.php?id=<?php echo htmlspecialchars($row['id']); ?>" style="text-decoration: none; color: #5a1212;">Baca Selengkapnya</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Tidak ada potensi yang ditemukan.</p>";
        }
        ?>
    </div>
    <div class="read-more-button">
            <a href="potensi.php">Lihat Semua Potensi</a>
        </div>
</div>


<div class="lokasi-container">
    <div class="lokasi-info-card">
        <h1>Batas Desa:</h1>
        <div class="batas-grid">
            <div class="batas-item">
                <p><strong>Utara</strong></p>
                <p>DESA NAGA UTARA</p>
            </div>
            <div class="batas-item">
                <p><strong>Timur</strong></p>
                <p>DESA NAGA TIMUR</p>
            </div>
            <div class="batas-item">
                <p><strong>Selatan</strong></p>
                <p>DESA NAGA SELATAN</p>
            </div>
            <div class="batas-item">
                <p><strong>Barat</strong></p>
                <p>DESA NAGA BARAT</p>
            </div>
        </div>
        <div class="info-lainnya">
            <div class="info-item">
                <p><strong>Luas Desa:</strong></p>
                <p>54,482.300 m²</p>
            </div>
            <hr>
            <div class="info-item">
                <p><strong>Jumlah Lapangan Pekerjaan:</strong></p>
                <p>19.000.000 Lapangan Pekerjaan</p>
            </div>
        </div>
    </div>
    <div class="lokasi-peta-card">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13698.027674435898!2d111.85389773691743!3d-7.583766608742392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784ace60239ad5%3A0x3bf4855a64a6b84d!2sSelorejo%2C%20Kec.%20Bagor%2C%20Kabupaten%20Nganjuk%2C%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1758500496062!5m2!1sid!2sid"
            width="100%" 
            height="100%" 
            style="border:0;"
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        ></iframe>
    </div>
</div>
  <?php include 'footer.php'; ?>

</body>
</html>
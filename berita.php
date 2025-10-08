<?php
require_once 'config.php';

$limit = 24;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    $countStmt = $pdo->prepare("SELECT COUNT(*) AS total FROM berita");
    $countStmt->execute();
    $totalData = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalData / $limit);

    $sql = "SELECT id, judul, isi_berita, gambar, tanggal_dibuat FROM berita ORDER BY tanggal_dibuat DESC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Berita Desa</title>
<style>
body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.header {
    text-align: center;
    margin-bottom: 40px;
    padding: 0 10px;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.news-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background: white;
}

.news-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.news-content {
    padding: 15px;
}

.news-content h3 {
    margin-top: 0;
    font-size: 1.2em;
}

.news-content p {
    font-size: 0.9em;
    color: #555;
    line-height: 1.4;
}

.news-date {
    font-size: 0.8em;
    color: #888;
    margin-bottom: 10px;
}

.pagination {
    display: flex;
    justify-content: center;
    margin: 30px 0;
    flex-wrap: wrap;
    gap: 5px;
}

.pagination a {
    color: #630808;
    padding: 10px 15px;
    text-decoration: none;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: background-color 0.3s, color 0.3s;
}

.pagination a:hover {
    background-color: #630808;
    color: white;
}

.pagination a.active {
    background-color: #630808;
    color: white;
    border-color: #630808;
}

@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr;
    }

    .news-card img {
        height: 180px;
    }

    .news-content h3 {
        font-size: 1em;
    }

    .news-content p {
        font-size: 0.85em;
    }

    .container {
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .news-card img {
        height: 150px;
    }

    .news-content h3 {
        font-size: 0.9em;
    }

    .news-content p {
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
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <div class="header">
        <h1>Berita Desa</h1>
        <p>Menyajikan informasi terbaru tentang peristiwa, berita terkini, dan artikel-artikel jurnalistik dari Desa Selorejo.</p>
    </div>

    <div class="news-grid">
        <?php
        if ($result && count($result) > 0) {
            foreach($result as $row) {
                ?>
                <div class="news-card">
                    <?php if ($row['gambar']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                    <?php endif; ?>
                    <div class="news-content">
                        <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                        <p class="news-date">
                            <?php
                            $date = new DateTime($row['tanggal_dibuat']);
                            echo $date->format('l, F j, Y');
                            ?>
                        </p>
                        <p><?php echo substr(htmlspecialchars($row['isi_berita']), 0, 150) . '...'; ?></p>
                        <a href="detail-berita.php?id=<?php echo htmlspecialchars($row['id']); ?>" style="text-decoration: none; color: #5a1212;">Baca Selengkapnya</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Tidak ada berita yang ditemukan.</p>";
        }
        ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

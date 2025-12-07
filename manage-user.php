<?php
require_once 'auth.php';
require_once 'config.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    if ($id == 1) {
        header("Location: manage-user.php?error=admin_protect");
        exit();
    }

    try {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error saat hapus user: " . $e->getMessage());
    }

    header("Location: manage-user.php");
    exit();
}

try {
    $sql_tabel = "SELECT id, username, email, role, created_at FROM users ORDER BY id ASC";
    $stmt_tabel = $pdo->query($sql_tabel);
    $user_list = $stmt_tabel->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error saat ambil daftar user: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna (Users)</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="aset/favicon.png" type="image/png">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .page-container {
            display: flex;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            box-sizing: border-box;
            
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 2em;
        }
        h2 {
            font-size: 1.5em;
            color: #333;
            margin-top: 0;
        }
        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .card-header-flex h2 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        
        /* --- PERBAIKAN TAMPILAN AKSI --- */
        .aksi {
            white-space: nowrap;
            display: flex; /* Menggunakan Flexbox untuk tombol */
            gap: 8px; /* Memberi jarak antar tombol */
            padding: 8px 0;
        }
        .aksi a, .aksi .btn-delete {
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px; /* Jarak antara ikon dan teks */
        }
        
        .aksi a.edit { 
            background: #2980b9; 
            color: white; 
            border: 1px solid #2980b9;
        }
        .aksi a.edit:hover {
            background: #3498db;
        }
        
        .aksi .btn-delete {
            background: #c0392b;
            color: white;
            border: 1px solid #c0392b;
        }
        .aksi .btn-delete:hover {
            background: #e74c3c;
        }
        /* --- AKHIR PERBAIKAN TAMPILAN AKSI --- */

        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-tambah {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-tambah:hover {
            background: #2ecc71;
        }
        .error-message { 
            background-color: #f8d7da; 
            color: #721c24; 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            border: 1px solid #f5c6cb; 
        }

        @media (max-width: 768px) {
            .page-container {
                flex-direction: column;
            }
            .main-content {
                padding: 15px;
            }
            .header-controls { 
                flex-direction: column; 
                align-items: stretch; 
                gap: 10px; 
            }
            .btn-tambah { 
                text-align: center; 
            }
            h1 {
                font-size: 1.5rem;
            }
            .card {
                padding: 15px;
            }
            .card-header-flex {
                 flex-direction: column; 
                 align-items: flex-start; 
                 gap: 10px; 
            }
            .card-header-flex .btn-tambah {
                align-self: flex-end;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead {
                display: none;
            }
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
                text-align: left;
                position: relative;
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
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <?php include 'sidebar.php'; ?> 
        <div class="main-content">
            <h1>Manajemen Pengguna</h1>
            <div class="header-controls">
                
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'admin_protect'): ?>
                <div class="error-message">
                    Anda tidak dapat menghapus pengguna admin utama (ID 1).
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header-flex">
                    <h2>Daftar Pengguna Sistem</h2>
                    <a href="manage-user-crud.php" class="btn-tambah">
                         Tambah Pengguna Baru
                    </a>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($user_list) > 0): ?>
                            <?php foreach ($user_list as $row): ?>
                                <tr>
                                    <td data-label="ID"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td data-label="Username"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td data-label="Role"><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
                                    <td data-label="Dibuat Pada"><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                                    <td data-label="Aksi" class="aksi">
                                        
                                        <a href="manage-user-crud.php?edit=<?php echo htmlspecialchars($row['id']); ?>" class="edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        
                                        <?php if ($row['id'] != 1): ?>
                                            <a href='manage-user.php?hapus=<?php echo htmlspecialchars($row['id']); ?>' class="btn-delete" onclick='return confirm("Yakin ingin menghapus user <?php echo htmlspecialchars($row['username']); ?>?")'>
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-komentar">Tidak ada pengguna yang terdaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
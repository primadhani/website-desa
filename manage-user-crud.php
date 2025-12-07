<?php
require_once 'auth.php';
require_once 'config.php';

// --- FUNGSI TAMBAH USER BARU ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_plain = $_POST['password'];
    $role = $_POST['role'];

    if (empty($password_plain)) {
        die("Password wajib diisi.");
    }
    
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $password_hashed, $role]);
    } catch (PDOException $e) {
        die("Error saat tambah user: " . $e->getMessage());
    }

    header("Location: manage-user.php");
    exit();
}

// --- FUNGSI AMBIL DATA UNTUK EDIT ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        $sql = "SELECT id, username, email, role FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$edit_data) {
             header("Location: manage-user.php");
             exit();
        }
    } catch (PDOException $e) {
        die("Error saat ambil data edit: " . $e->getMessage());
    }
}

// --- FUNGSI UPDATE USER ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password_plain = $_POST['password'];

    try {
        if (!empty($password_plain)) {
            $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $password_hashed, $role, $id]);
        } else {
            $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $role, $id]);
        }
    } catch (PDOException $e) {
        die("Error saat update user: " . $e->getMessage());
    }

    header("Location: manage-user.php");
    exit();
}

// Tentukan judul halaman
$page_title = $edit_data ? 'Edit Pengguna: ' . htmlspecialchars($edit_data['username']) : 'Tambah Pengguna Baru';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="aset/favicon.png" type="image/png">

    <style>
        /* CSS DISAMAKAN DENGAN manage-lembaga.php */
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
            max-width: 800px; /* Dikecilkan agar formulir lebih fokus */
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
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
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
            margin-top: 20px;
        }
        button:hover { background: #2ecc71; }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
        .btn-back:hover {
            background: #5a6268;
        }

        /* MEDIA QUERIES Sederhana untuk formulir */
        @media (max-width: 768px) {
            .main-content { padding: 15px; }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        

        <div class="card">
            <h2><?php echo $page_title; ?></h2>
            <form action="manage-user-crud.php" method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_data['id']); ?>">
                    <input type="hidden" name="update" value="1">
                <?php else: ?>
                    <input type="hidden" name="tambah" value="1">
                <?php endif; ?>
                
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($edit_data['username'] ?? ''); ?>" required>
                
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($edit_data['email'] ?? ''); ?>" required>
                
                <label>Password <?php echo $edit_data ? '(Kosongkan jika tidak ingin diubah)' : ''; ?></label>
                <input type="password" name="password" <?php echo $edit_data ? '' : 'required'; ?>>
                
                <label>Role (Hak Akses)</label>
                <select name="role" required>
                    <option value="" disabled selected>Pilih Hak Akses</option>
                    <?php
                    $roles = ["admin", "user"]; 
                    foreach ($roles as $r) {
                        $is_selected = ($edit_data && $edit_data['role'] == $r) ? 'selected' : '';
                        echo "<option value=\"$r\" $is_selected>" . ucfirst($r) . "</option>";
                    }
                    ?>
                </select>

                <button class="btn-back" onclick="window.location.href='manage-user.php';">Kembali</button>
                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>
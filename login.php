<?php
session_start();
require 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username'];
    $password_input = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            'username' => $username_or_email,
            'email' => $username_or_email
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password_input, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: manage-potensi.php");
                exit();
            } else {
                $error = "Kata sandi salah.";
            }
        } else {
            $error = "Username atau email tidak ditemukan.";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login Admin</title>
    <link rel="icon" href="aset/favicon.png" type="image/png">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 10px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-header h2 {
            margin: 0 0 10px;
            font-size: 2em;
            color: #5a1212;
        }
        .login-header p {
            color: #888;
            margin-bottom: 30px;
            font-size: 0.95em;
        }
        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 0.9em;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .login-btn {
            width: 100%;
            padding: 15px;
            background-color: #5a1212;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-btn:hover {
            background-color: #5a1212;
        }
        .error-message {
            color: #d9534f;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: block;
            font-size: 0.9em;
        }
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            .login-header h2 {
                font-size: 1.6em;
            }
            .login-btn {
                padding: 12px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>Masuk Admin</h2>
            <p>Silakan masuk untuk mengelola konten website</p>
        </div>
        <?php if (!empty($error)): ?>
            <span class="error-message"><?php echo $error; ?></span>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Username atau Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Masuk</button>
        </form>
    </div>
</div>
</body>
</html>

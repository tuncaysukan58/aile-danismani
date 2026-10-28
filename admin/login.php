<?php
require_once __DIR__ . '/../config/config.php';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: ' . SITE_URL . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['name'];

            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        } else {
            $error = 'Kullanıcı adı veya şifre hatalı!';
        }
    } else {
        $error = 'Lütfen tüm alanları doldurunuz.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli Girişi — Aile Danışmanı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
    <style>
        body {
            background: #F4F6F8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .login-box {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            margin: 0 0 8px;
            color: #1E293B;
            font-size: 1.5rem;
        }
        .login-header p {
            margin: 0;
            color: #64748B;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            font-size: 0.95rem;
            box-sizing: border-box;
            outline: none;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #855838;
            box-shadow: 0 0 0 3px rgba(133, 88, 56, 0.15);
        }
        .btn-submit {
            width: 100%;
            background: #855838;
            color: #FFFFFF;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit:hover {
            background: #6E462A;
        }
        .error-alert {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }
        .default-hint {
            margin-top: 24px;
            background: #F8FAFC;
            border: 1px dashed #CBD5E1;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            font-size: 0.8rem;
            color: #64748B;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-header">
        <h2>Yönetim Paneli</h2>
        <p>Lütfen yönetici giriş bilgilerinizi yazınız</p>
    </div>

    <?php if ($error): ?>
    <div class="error-alert">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" class="form-control" required placeholder="admin" autofocus>
        </div>

        <div class="form-group">
            <label for="password">Şifre</label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-submit">Giriş Yap</button>
    </form>

    <div class="default-hint">
        Varsayılan Kullanıcı: <strong>admin</strong> | Şifre: <strong>admin123</strong>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="<?= SITE_URL ?>/" style="color: #64748B; font-size: 0.85rem; text-decoration: none;">← Web Sitesine Dön</a>
    </div>
</div>

</body>
</html>

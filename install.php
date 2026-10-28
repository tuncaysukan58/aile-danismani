<?php
/**
 * Kolay Kurulum Sihirbazı (cPanel & Paylaşımlı Hosting İçin)
 * Bu dosya cPanel MySQL veritabanınızı otomatik kurar ve config.php dosyasını günceller.
 */

$message = '';
$error = '';
$isInstalled = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = trim($_POST['db_pass'] ?? '');
    $adminPass = trim($_POST['admin_pass'] ?? 'admin123');

    if ($dbName && $dbUser) {
        try {
            // MySQL Bağlantısını Test Et
            $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]);

            // Schema SQL dosyasını çalıştır
            $schemaFile = __DIR__ . '/database/schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $pdo->exec($sql);
            }

            // Admin kullanıcısı ekle
            $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (username, password, name, email) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)");
            $stmt->execute(['admin', $hashedPass, 'Yönetici', 'admin@example.com']);

            // config/config.php dosyasını güncelle
            $configPath = __DIR__ . '/config/config.php';
            $configContent = file_get_contents($configPath);
            
            // DB_TYPE değiştir
            $configContent = preg_replace("/define\('DB_TYPE', '.*?'\);/", "define('DB_TYPE', 'mysql');", $configContent);
            $configContent = preg_replace("/define\('DB_HOST', '.*?'\);/", "define('DB_HOST', '{$dbHost}');", $configContent);
            $configContent = preg_replace("/define\('DB_NAME', '.*?'\);/", "define('DB_NAME', '{$dbName}');", $configContent);
            $configContent = preg_replace("/define\('DB_USER', '.*?'\);/", "define('DB_USER', '{$dbUser}');", $configContent);
            $configContent = preg_replace("/define\('DB_PASS', '.*?'\);/", "define('DB_PASS', '{$dbPass}');", $configContent);

            file_put_contents($configPath, $configContent);

            // Örnek verileri tohumla
            require_once __DIR__ . '/database/init_db.php';
            initialize_database();

            $isInstalled = true;
            $message = 'Tebrikler! Kurulum başarıyla tamamlandı. Web siteniz ve yönetim paneliniz hazır.';
        } catch (PDOException $e) {
            $error = 'Veritabanına bağlanılamadı: ' . $e->getMessage() . '<br>Lütfen cPanel MySQL bilgilerinizi kontrol ediniz.';
        }
    } else {
        $error = 'Lütfen Veritabanı Adı ve Kullanıcı Adı alanlarını doldurunuz.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum Sihirbazı — Aile Danışmanı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #F8FAFC; color: #1E293B; margin: 0; padding: 40px 20px; display: flex; justify-content: center; }
        .setup-box { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); max-width: 520px; width: 100%; padding: 40px; box-sizing: border-box; }
        h1 { margin: 0 0 10px; font-size: 1.6rem; color: #855838; }
        p { color: #64748B; font-size: 0.95rem; line-height: 1.5; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; }
        input:focus { border-color: #855838; outline: none; }
        .btn { background: #855838; color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 1rem; font-weight: 600; width: 100%; cursor: pointer; }
        .btn:hover { background: #6E462A; }
        .alert-success { background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; padding: 16px; border-radius: 8px; margin-bottom: 24px; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 16px; border-radius: 8px; margin-bottom: 24px; }
    </style>
</head>
<body>

<div class="setup-box">
    <h1>Aile Danışmanı Kurulum Sihirbazı</h1>
    <p>cPanel paylaşımlı hostinginizde oluşturduğunuz MySQL veritabanı bilgilerini girerek kurulumu 1 saniyede tamamlayabilirsiniz.</p>

    <?php if ($message): ?>
    <div class="alert-success">
        <?= $message ?>
        <div style="margin-top: 20px; display:flex; gap:12px;">
            <a href="index.php" style="background:#166534; color:#fff; padding:10px 18px; text-decoration:none; border-radius:6px; font-weight:600;">Siteyi Aç</a>
            <a href="admin/login.php" style="background:#855838; color:#fff; padding:10px 18px; text-decoration:none; border-radius:6px; font-weight:600;">Yönetim Paneli</a>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!$isInstalled): ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="db_host">MySQL Sunucu (Host)</label>
            <input type="text" id="db_host" name="db_host" value="localhost" required>
        </div>

        <div class="form-group">
            <label for="db_name">Veritabanı Adı (DB Name) *</label>
            <input type="text" id="db_name" name="db_name" placeholder="cpanelkullanici_ailedb" required>
        </div>

        <div class="form-group">
            <label for="db_user">Veritabanı Kullanıcısı (DB User) *</label>
            <input type="text" id="db_user" name="db_user" placeholder="cpanelkullanici_aileusr" required>
        </div>

        <div class="form-group">
            <label for="db_pass">Veritabanı Şifresi (DB Password)</label>
            <input type="password" id="db_pass" name="db_pass" placeholder="Veritabanı şifresi">
        </div>

        <div class="form-group">
            <label for="admin_pass">Admin Paneli Şifresi (Varsayılan: admin123)</label>
            <input type="text" id="admin_pass" name="admin_pass" value="admin123">
        </div>

        <button type="submit" class="btn">Otomatik Kurulumu Başlat</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>

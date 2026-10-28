<?php
/**
 * Aile Danışmanı Web Sitesi - Yapılandırma & Veritabanı Bağlantısı
 * Hem paylaşımlı hosting (cPanel MySQL) hem de yerel SQLite ile tam uyumlu.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hata raporlama (Geliştirme aşamasında açık, canlıda kapalı tutulabilir)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// -------------------------------------------------------------
// VERİTABANI AYARLARI
// cPanel'e yüklediğinizde 'DB_TYPE' => 'mysql' yapıp bilgilerinizi giriniz.
// -------------------------------------------------------------
define('DB_TYPE', 'sqlite'); // 'mysql' veya 'sqlite'

// MySQL Kullanılacaksa:
define('DB_HOST', 'localhost');
define('DB_NAME', 'ailedanisman_db');
define('DB_USER', 'ailedanisman_usr');
define('DB_PASS', 'Sifre_123456');
define('DB_CHARSET', 'utf8mb4');

// SQLite Dosya Yolu:
define('SQLITE_FILE', __DIR__ . '/../database/app.sqlite');

// Site Temel URL'si (Otomatik tespit)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = str_replace('\\', '/', $scriptDir);
// Eğer admin dizini içindeyse ana dizini bul
$baseDir = preg_replace('#/(admin|api)(/.*)?$#i', '', $scriptDir);
$baseDir = rtrim($baseDir, '/');
define('SITE_URL', $protocol . $host . $baseDir);
define('ADMIN_URL', SITE_URL . '/admin');

/**
 * PDO Veritabanı Bağlantısını Döndürür
 */
function get_db(): PDO {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_TYPE === 'mysql') {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } else {
            // SQLite
            $dir = dirname(SQLITE_FILE);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $pdo = new PDO('sqlite:' . SQLITE_FILE, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->exec("PRAGMA foreign_keys = ON;");
        }
    } catch (PDOException $e) {
        die("Veritabanı bağlantı hatası: " . $e->getMessage() . "<br><small>cPanel hostinginiz için config/config.php dosyasındaki veritabanı ayarlarını kontrol ediniz.</small>");
    }

    return $pdo;
}

/**
 * Ayar değerini veritabanından çeker
 */
function get_setting(string $key, string $default = ''): string {
    static $cache = null;
    $db = get_db();

    if ($cache === null) {
        try {
            $stmt = $db->query("SELECT `key`, `value` FROM settings");
            $cache = [];
            while ($row = $stmt->fetch()) {
                $cache[$row['key']] = $row['value'];
            }
        } catch (Exception $e) {
            $cache = [];
        }
    }

    return $cache[$key] ?? $default;
}

/**
 * Ayar değerini kaydeder veya günceller
 */
function set_setting(string $key, string $value): bool {
    $db = get_db();
    try {
        if (DB_TYPE === 'mysql') {
            $stmt = $db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
            return $stmt->execute([$key, $value]);
        } else {
            $stmt = $db->prepare("INSERT OR REPLACE INTO settings (`key`, `value`) VALUES (?, ?)");
            return $stmt->execute([$key, $value]);
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Güvenli girdi temizleme
 */
function clean(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * URL Slug oluşturucu
 */
function slugify(string $text): string {
    $find = ['Ç', 'ç', 'Ğ', 'ğ', 'ı', 'I', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü'];
    $replace = ['c', 'c', 'g', 'g', 'i', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u'];
    $text = str_replace($find, $replace, $text);
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text ?: 'n-a');
}

/**
 * Tarih Türkçe Formatı
 */
function format_date_tr(string $date): string {
    if (!$date) return '';
    $timestamp = strtotime($date);
    $months = [
        1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran',
        'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
    ];
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)] ?? '';
    $year = date('Y', $timestamp);
    return "$day $month $year";
}

/**
 * WhatsApp Yönlendirme URL'si (Kullanıcı Adı veya Telefon Numarası Destekli)
 */
function get_whatsapp_url(string $customText = ''): string {
    $wa = trim(get_setting('whatsapp_number', '@caglatokyurek'));
    $cleanUser = ltrim($wa, '@');
    $textParam = $customText ? ('?text=' . urlencode($customText)) : '';

    // Eğer harf içeriyorsa veya @ ile başlıyorsa kullanıcı adı linki (wa.me/kullaniciadi) oluştur
    if (preg_match('/[a-zA-Z]/', $cleanUser)) {
        return "https://wa.me/{$cleanUser}" . $textParam;
    }

    // Yalnızca rakam ise telefon numarası linki oluştur
    $digits = preg_replace('/[^0-9]/', '', $cleanUser);
    if (str_starts_with($digits, '0')) {
        $digits = '90' . substr($digits, 1);
    }
    return "https://api.whatsapp.com/send?phone={$digits}" . ($customText ? ('&text=' . urlencode($customText)) : '');
}

/**
 * WhatsApp Görünen Metin (@kullaniciadi veya Telefon)
 */
function get_whatsapp_display(): string {
    $wa = trim(get_setting('whatsapp_number', '@caglatokyurek'));
    $clean = ltrim($wa, '@');
    if (preg_match('/[a-zA-Z]/', $clean)) {
        return '@' . $clean;
    }
    return $wa;
}

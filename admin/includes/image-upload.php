<?php
/**
 * Admin Görsel Yükleme ve Galeri Yardımcıları
 * - Güvenli yükleme: yalnızca JPG / PNG / WEBP, en fazla 5 MB, gerçek resim kontrolü
 * - Dosyalar assets/uploads/<klasör>/ altına rastgele isimle kaydedilir
 * - Galeri: sitedeki mevcut görselleri listeler (assets/images + assets/uploads)
 */

const IMG_MAX_BYTES = 5 * 1024 * 1024;
const IMG_ALLOWED_MIME = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
];

/** Proje kök dizini */
function img_root(): string {
    return realpath(__DIR__ . '/../..');
}

/**
 * $_FILES içindeki görseli doğrular ve kaydeder.
 * @return array{ok:bool, path?:string, error?:string}  path: 'assets/uploads/...' (site köküne göre)
 */
function handle_image_upload(string $field, string $folder = 'services'): array {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false]; // Dosya seçilmemiş (hata değil)
    }

    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $msg = in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
            ? 'Görsel çok büyük (en fazla 5 MB).'
            : 'Görsel yüklenemedi (hata kodu: ' . (int)$f['error'] . ').';
        return ['ok' => false, 'error' => $msg];
    }
    if ($f['size'] > IMG_MAX_BYTES) {
        return ['ok' => false, 'error' => 'Görsel çok büyük (en fazla 5 MB).'];
    }
    if (!is_uploaded_file($f['tmp_name'])) {
        return ['ok' => false, 'error' => 'Geçersiz yükleme.'];
    }

    // Gerçek MIME türünü dosya içeriğinden tespit et (uzantıya güvenme)
    $info = @getimagesize($f['tmp_name']);
    $mime = $info['mime'] ?? '';
    if (!isset(IMG_ALLOWED_MIME[$mime])) {
        return ['ok' => false, 'error' => 'Yalnızca JPG, PNG veya WEBP görsel yükleyebilirsiniz.'];
    }

    $folder = preg_replace('/[^a-z0-9_-]/i', '', $folder) ?: 'misc';
    $relDir = 'assets/uploads/' . $folder;
    $absDir = img_root() . '/' . $relDir;
    if (!is_dir($absDir) && !mkdir($absDir, 0755, true)) {
        return ['ok' => false, 'error' => 'Yükleme klasörü oluşturulamadı.'];
    }

    $name = date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . IMG_ALLOWED_MIME[$mime];
    if (!move_uploaded_file($f['tmp_name'], $absDir . '/' . $name)) {
        return ['ok' => false, 'error' => 'Görsel kaydedilemedi. Klasör yazma iznini kontrol edin.'];
    }
    @chmod($absDir . '/' . $name, 0644);

    return ['ok' => true, 'path' => $relDir . '/' . $name];
}

/**
 * Sitedeki mevcut görselleri döndürür (yeniden kullanım için galeri).
 * Logo / ikon dosyaları listeden çıkarılır.
 * @return string[]  'assets/...' yolları, en yeniler başta
 */
function list_gallery_images(): array {
    $root = img_root();
    $files = [];
    $patterns = [
        $root . '/assets/uploads/*/*.{jpg,jpeg,png,webp}',
        $root . '/assets/images/*.{jpg,jpeg,png,webp}',
    ];
    foreach ($patterns as $p) {
        foreach (glob($p, GLOB_BRACE) ?: [] as $abs) {
            $base = basename($abs);
            if (preg_match('/^(logo|favicon|apple-touch)/i', $base)) continue;
            $files[$abs] = filemtime($abs);
        }
    }
    arsort($files);
    return array_map(fn($abs) => ltrim(str_replace('\\', '/', substr($abs, strlen($root))), '/'), array_keys($files));
}

/** Formdan gelen galeri seçiminin gerçekten galeride olduğunu doğrular */
function is_valid_gallery_choice(string $path): bool {
    return $path !== '' && in_array($path, list_gallery_images(), true);
}

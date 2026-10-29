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


/**
 * Görsel seçici arayüzünü basar: önizleme + yükleme butonu + mevcut görseller galerisi.
 * Form etiketi enctype="multipart/form-data" olmalıdır.
 * Form alanları: image_file (dosya), gallery_image (galeri seçimi)
 */
function render_image_picker(string $currentImage, string $defaultImage, string $label = 'Kapak Görseli', string $hint = 'önerilen oran 4:3 (ör. 1200×900)'): void {
    $gallery = list_gallery_images();
    $shown = $currentImage ?: $defaultImage;
    $e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    ?>
    <div class="admin-form-group">
        <label><?= $e($label) ?></label>
        <div class="img-picker">
            <div class="img-preview">
                <img src="<?= SITE_URL ?>/<?= $e($shown) ?>" alt="Seçili görsel" class="js-img-preview">
                <span class="img-preview-label js-img-label"><?= $currentImage ? 'Mevcut görsel' : 'Varsayılan görsel' ?></span>
            </div>

            <label class="img-upload-btn">
                <input type="file" name="image_file" class="js-img-file" accept="image/jpeg,image/png,image/webp">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                <span class="js-img-filename">Bilgisayardan Görsel Yükle</span>
            </label>
            <small class="img-hint">JPG, PNG veya WEBP · en fazla 5 MB · <?= $e($hint) ?></small>

            <input type="hidden" name="gallery_image" class="js-img-gallery-value" value="">

            <?php if (!empty($gallery)): ?>
            <details class="img-gallery-wrap">
                <summary>veya mevcut görsellerden seç (<?= count($gallery) ?>)</summary>
                <div class="img-gallery">
                    <?php foreach ($gallery as $g): ?>
                    <button type="button" class="img-gallery-item <?= $g === $currentImage ? 'selected' : '' ?>" data-path="<?= $e($g) ?>" title="<?= $e(basename($g)) ?>">
                        <img src="<?= SITE_URL ?>/<?= $e($g) ?>" alt="" loading="lazy">
                    </button>
                    <?php endforeach; ?>
                </div>
            </details>
            <?php endif; ?>
        </div>
    </div>
    <script>
    (function () {
        var root = document.currentScript.previousElementSibling;
        var fileInput = root.querySelector('.js-img-file');
        var fileName  = root.querySelector('.js-img-filename');
        var preview   = root.querySelector('.js-img-preview');
        var label     = root.querySelector('.js-img-label');
        var hidden    = root.querySelector('.js-img-gallery-value');
        var items     = root.querySelectorAll('.img-gallery-item');
        function clearSel() { items.forEach(function (b) { b.classList.remove('selected'); }); }

        fileInput.addEventListener('change', function () {
            var f = this.files && this.files[0];
            if (!f) return;
            if (f.size > 5 * 1024 * 1024) { alert('Görsel 5 MB\'tan büyük olamaz.'); this.value = ''; return; }
            preview.src = URL.createObjectURL(f);
            label.textContent = 'Yeni yüklenecek';
            fileName.textContent = f.name;
            hidden.value = '';
            clearSel();
        });

        items.forEach(function (btn) {
            btn.addEventListener('click', function () {
                clearSel();
                btn.classList.add('selected');
                hidden.value = btn.dataset.path;
                preview.src = btn.querySelector('img').src;
                label.textContent = 'Galeriden seçildi';
                fileInput.value = '';
                fileName.textContent = 'Bilgisayardan Görsel Yükle';
            });
        });
    })();
    </script>
    <?php
}

/**
 * Formdan gelen görseli çözümler: yeni yükleme > galeri seçimi > mevcut > varsayılan
 * @return array{image:string, error:string}
 */
function resolve_image_from_form(string $currentImage, string $defaultImage, string $folder): array {
    $upload = handle_image_upload('image_file', $folder);
    if (!empty($upload['error'])) {
        return ['image' => $currentImage ?: $defaultImage, 'error' => $upload['error']];
    }
    if ($upload['ok']) {
        return ['image' => $upload['path'], 'error' => ''];
    }
    $choice = trim($_POST['gallery_image'] ?? '');
    if (is_valid_gallery_choice($choice)) {
        return ['image' => $choice, 'error' => ''];
    }
    return ['image' => $currentImage ?: $defaultImage, 'error' => ''];
}

<?php
require_once __DIR__ . '/config/config.php';

http_response_code(404);

$pageTitle = 'Sayfa Bulunamadı — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = 'Aradığınız sayfa bulunamadı.';
$noIndex = true;
$canonicalUrl = SITE_URL . '/';

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 90px 24px 120px; max-width: 640px; text-align: center;">
    <img src="<?= SITE_URL ?>/assets/images/logo-mark.png" alt="" width="110" height="96" style="margin: 0 auto 24px; opacity: 0.9;">
    <span class="eyebrow">Hata 404</span>
    <h1 style="font-size: 2.4rem; margin: 12px 0 14px;">Aradığınız sayfa bulunamadı</h1>
    <p style="color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 32px;">
        Sayfa taşınmış ya da kaldırılmış olabilir. Aşağıdaki bağlantılardan devam edebilirsiniz.
    </p>
    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
        <a href="<?= SITE_URL ?>/" class="btn btn-primary">Anasayfaya Dön</a>
        <a href="<?= SITE_URL ?>/randevu" class="btn btn-secondary">Randevu Al</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

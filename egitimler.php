<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Eğitimlerim ve Danışmanlık Hizmetleri — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = 'Boşanma danışmanlığı, aile ve çift danışmanlığı, çocuk değerlendirme testleri, MOXO dikkat testi, Denver II, oyun terapisi ve eğitim danışmanlığı.';

$db = get_db();

// Kategori Filtresi
$category = clean($_GET['kat'] ?? '');
if ($category) {
    $stmt = $db->prepare("SELECT * FROM services WHERE is_active = 1 AND category = ? ORDER BY display_order ASC");
    $stmt->execute([$category]);
} else {
    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC");
}
$services = $stmt->fetchAll();

// Mevcut Kategoriler
$stmtCat = $db->query("SELECT DISTINCT category FROM services WHERE is_active = 1");
$categories = $stmtCat->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px;">
    
    <div class="section-header">
        <span class="eyebrow">Programlar & Seanslar</span>
        <h1>Eğitimlerim ve Hizmetlerim</h1>
        <p>Aile bağlarınızı tazeleyen, çocuk ve ergen iletişiminde rehberlik sunan ve evlilik dinamiklerini güçlendiren programlar.</p>
    </div>

    <!-- Kategori Filtre Butonları -->
    <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 40px; flex-wrap: wrap;">
        <a href="<?= SITE_URL ?>/egitimler" class="btn <?= empty($category) ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 8px 20px; font-size: 0.875rem;">
            Tümü
        </a>
        <?php foreach ($categories as $cat): ?>
        <a href="<?= SITE_URL ?>/egitimler?kat=<?= urlencode($cat) ?>" class="btn <?= $category === $cat ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 8px 20px; font-size: 0.875rem;">
            <?= htmlspecialchars($cat) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Hizmet / Eğitim Kartları -->
    <div class="cards-grid">
        <?php if (empty($services)): ?>
            <div style="grid-column: span 3; text-align: center; padding: 40px; color: var(--text-muted);">
                Bu kategoride henüz yayınlanmış bir eğitim bulunmuyor.
            </div>
        <?php else: ?>
            <?php foreach ($services as $svc): ?>
            <div class="service-card">
                <div class="card-image-box">
                    <img src="<?= SITE_URL ?>/<?= htmlspecialchars($svc['image'] ?: 'assets/images/seminar.jpg') ?>" alt="<?= htmlspecialchars($svc['title']) ?>">
                    <span class="card-badge"><?= htmlspecialchars($svc['category']) ?></span>
                </div>
                <div class="card-body">
                    <h3><?= htmlspecialchars($svc['title']) ?></h3>
                    <p><?= htmlspecialchars($svc['summary']) ?></p>
                    <div class="card-footer">
                        <span class="card-meta">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <?= htmlspecialchars($svc['duration']) ?>
                        </span>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= SITE_URL ?>/egitim/<?= urlencode($svc['slug']) ?>" class="btn btn-ghost" style="padding: 6px 14px; font-size: 0.825rem;">İncele</a>
                            <a href="<?= SITE_URL ?>/randevu?service_id=<?= $svc['id'] ?>" class="btn btn-primary" style="padding: 6px 14px; font-size: 0.825rem;">Randevu Al</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

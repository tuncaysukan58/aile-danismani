<?php
require_once __DIR__ . '/config/config.php';

$slug = clean($_GET['slug'] ?? '');
$db = get_db();

$stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: " . SITE_URL . "/egitimler");
    exit;
}

$pageTitle = $service['title'] . ' — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = $service['summary'];
$canonicalUrl = SITE_URL . '/egitim/' . $service['slug'];
$ogImage = SITE_URL . '/' . ($service['image'] ?: 'assets/images/seminar.jpg');

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px;">
    <div style="margin-bottom: 24px;">
        <a href="<?= SITE_URL ?>/egitimler" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; color: var(--text-muted);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
            <span>Tüm Eğitimlere Dön</span>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 50px; align-items: flex-start;">
        <div>
            <span class="eyebrow"><?= htmlspecialchars($service['category']) ?></span>
            <h1 style="font-size: 2.8rem; margin-bottom: 20px;"><?= htmlspecialchars($service['title']) ?></h1>
            <p style="font-size: 1.15rem; color: var(--text-secondary); line-height: 1.7; margin-bottom: 30px;">
                <?= htmlspecialchars($service['summary']) ?>
            </p>

            <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 40px; box-shadow: var(--shadow-md);">
                <img src="<?= SITE_URL ?>/<?= htmlspecialchars($service['image'] ?: 'assets/images/seminar.jpg') ?>" alt="<?= htmlspecialchars($service['title']) ?>" style="width: 100%; max-height: 400px; object-fit: cover;">
            </div>

            <div style="font-size: 1.05rem; line-height: 1.8; color: var(--text-primary);">
                <?= nl2br(htmlspecialchars($service['content'])) ?>
            </div>
        </div>

        <!-- Yan Bilgi Kutusu -->
        <div style="position: sticky; top: 110px;">
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.3rem; margin-bottom: 20px;">Program Detayları</h3>
                
                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 30px; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                        <span style="color: var(--text-muted);">Süre:</span>
                        <strong><?= htmlspecialchars($service['duration']) ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                        <span style="color: var(--text-muted);">Format:</span>
                        <strong><?= htmlspecialchars($service['price'] ?: 'Yüz Yüze / Online') ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px;">
                        <span style="color: var(--text-muted);">Uzman:</span>
                        <strong><?= htmlspecialchars(get_setting('counselor_name')) ?></strong>
                    </div>
                </div>

                <a href="<?= SITE_URL ?>/randevu?service_id=<?= $service['id'] ?>" class="btn btn-primary btn-block" style="margin-bottom: 12px;">
                    Bu Program İçin Randevu Al
                </a>

                <?php 
                $waText = "Merhaba, '" . $service['title'] . "' hakkında detaylı bilgi ve danışmanlık koşullarını öğrenmek istiyorum.";
                ?>
                <a href="<?= get_whatsapp_url($waText) ?>" target="_blank" class="btn btn-secondary btn-block" style="background:#25D366; color:#fff; border-color:#25D366;">
                    WhatsApp'tan Soru Sor (<?= htmlspecialchars(get_whatsapp_display()) ?>)
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

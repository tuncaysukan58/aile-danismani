<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = get_setting('site_title', 'Uzm. Aile Danışmanı Elif Karaca | Aile & Çift Terapisi');
$metaDescription = get_setting('hero_mission', 'Aile bağlarını güçlendiren, ebeveynlik yolculuğunuza rehberlik eden profesyonel danışmanlık.');

$db = get_db();

// Öne Çıkan Hizmetler/Eğitimler (En fazla 3 adet)
$stmtServices = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC LIMIT 3");
$featuredServices = $stmtServices->fetchAll();

// Son Blog Yazıları (3 adet)
$stmtPosts = $db->query("SELECT * FROM blog_posts WHERE status = 'published' ORDER BY id DESC LIMIT 3");
$recentPosts = $stmtPosts->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="eyebrow"><?= htmlspecialchars(get_setting('eyebrow', 'Aile & Çift Danışmanı, Eğitmen')) ?></span>
            <h1><?= htmlspecialchars(get_setting('counselor_name', 'Elif Karaca')) ?></h1>
            <p class="hero-mission">
                <?= nl2br(htmlspecialchars(get_setting('hero_mission', 'Aile içi dinamikleri güçlendiren, ebeveynlik yolculuğunuza rehberlik eden ve ilişkilerdeki tıkanıklıkları sevgi ve bilimsel temellerle çözen bütüncül bir yaklaşım.'))) ?>
            </p>
            <div class="hero-actions">
                <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Randevu Oluştur</span>
                </a>
                <a href="<?= SITE_URL ?>/egitimler" class="btn btn-secondary">
                    <span>Eğitimleri İncele</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <div class="hero-portrait">
            <div class="hero-image-wrapper">
                <img src="<?= SITE_URL ?>/assets/images/counselor.jpg" alt="<?= htmlspecialchars(get_setting('counselor_name')) ?>">
            </div>
            <div class="hero-badge-tag">
                <strong><?= htmlspecialchars(get_setting('counselor_name', 'Elif Karaca')) ?></strong>
                <span><?= htmlspecialchars(get_setting('counselor_title', 'Uzm. Aile Danışmanı')) ?></span>
            </div>
        </div>
    </div>
</section>

<!-- İstatistik Şeridi -->
<div class="container">
    <div class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <b><?= htmlspecialchars(get_setting('stat_experience', '12+ Yıl')) ?></b>
                <span>Mesleki Deneyim</span>
            </div>
            <div class="stat-item">
                <b><?= htmlspecialchars(get_setting('stat_clients', '2500+')) ?></b>
                <span>Danışan & Seans</span>
            </div>
            <div class="stat-item">
                <b><?= htmlspecialchars(get_setting('stat_seminars', '40+')) ?></b>
                <span>Seminer & Atölye</span>
            </div>
            <div class="stat-item">
                <b><?= htmlspecialchars(get_setting('stat_followers', '250K+')) ?></b>
                <span>Sosyal Takipçi</span>
            </div>
        </div>
    </div>
</div>

<!-- Hakkımda Özet -->
<section class="about-teaser">
    <div class="container about-grid">
        <div class="about-image-wrapper">
            <img src="<?= SITE_URL ?>/assets/images/therapy.jpg" alt="Aile ve Çift Seansı">
        </div>
        <div class="about-content">
            <span class="eyebrow">Yaklaşımım & Felsefem</span>
            <h2>İlişkilerde Onarım ve Güvenli Bir Liman İnşa Etmek</h2>
            <p>
                <?= htmlspecialchars(get_setting('about_text_short', 'Her ailenin kendi içinde eşsiz bir dengesi ve aşabileceği dönüm noktaları vardır. Amacım, birbirinizi yargılamadan dinleyebileceğiniz ve sevgi bağlarını tazeleyebileceğiniz güvenli bir terapi alanı sunmaktır.')) ?>
            </p>
            <ul class="features-list">
                <li>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Gottman Çift Terapisi ve Şema Terapi temelli kanıta dayalı yöntemler</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Yüz yüze (Kadıköy/İstanbul) ve dünyanın her yerinden online seans imkanı</span>
                </li>
                <li>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Koşulsuz kabul, tam gizlilik ve etik değerlere bağlı profesyonel süreç</span>
                </li>
            </ul>
            <div style="margin-top: 24px;">
                <a href="<?= SITE_URL ?>/hakkimda" class="btn btn-secondary">Özgeçmiş & Detaylar</a>
            </div>
        </div>
    </div>
</section>

<!-- Öne Çıkan Eğitimler & Çalışmalar -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Çalışma Alanlarım</span>
            <h2>Danışmanlık ve Eğitim Programları</h2>
            <p>Bireysel, çift ve ebeveynlere özel olarak yapılandırılmış profesyonel seans ve seminerler.</p>
        </div>

        <div class="cards-grid">
            <?php foreach ($featuredServices as $svc): ?>
            <div class="service-card">
                <div class="card-image-box">
                    <img src="<?= SITE_URL ?>/<?= htmlspecialchars($svc['image'] ?: 'assets/images/counselor.jpg') ?>" alt="<?= htmlspecialchars($svc['title']) ?>">
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
                        <a href="<?= SITE_URL ?>/randevu" class="btn btn-ghost" style="padding: 6px 16px; font-size: 0.85rem;">Randevu Al</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="<?= SITE_URL ?>/egitimler" class="btn btn-secondary">Tüm Eğitim ve Seansları Gör</a>
        </div>
    </div>
</section>

<!-- Son Blog Yazıları -->
<section class="blog-section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Faydalı Yazılar</span>
            <h2>Blog & Makaleler</h2>
            <p>Aile ilişkileri, ebeveynlik ve evlilik dinamikleri üzerine kaleme aldığım güncel yazılar.</p>
        </div>

        <div class="blog-grid">
            <?php foreach ($recentPosts as $post): ?>
            <article class="blog-card">
                <a href="<?= SITE_URL ?>/blog/<?= urlencode($post['slug']) ?>" class="blog-thumb">
                    <img src="<?= SITE_URL ?>/<?= htmlspecialchars($post['cover_image'] ?: 'assets/images/therapy.jpg') ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                </a>
                <div class="blog-body">
                    <span class="blog-category"><?= htmlspecialchars($post['category']) ?></span>
                    <h3>
                        <a href="<?= SITE_URL ?>/blog/<?= urlencode($post['slug']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <p><?= htmlspecialchars($post['summary']) ?></p>
                    <div class="blog-meta">
                        <span><?= format_date_tr($post['published_at'] ?: $post['created_at']) ?></span>
                        <span><?= htmlspecialchars($post['reading_time']) ?> okuma</span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Randevu Çağrı Şeridi -->
<section class="cta-banner">
    <div class="container">
        <h2>Birlikte İlk Adımı Atalım</h2>
        <p>İlişkinizde veya aile hayatınızda yaşadığınız zorlukları ertelemeyin. Müsait randevu saatlerini inceleyip hemen yerinizi ayırtabilirsiniz.</p>
        <div class="cta-actions">
            <a href="<?= SITE_URL ?>/randevu" class="btn btn-light">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <span>Hemen Randevu Al</span>
            </a>
            <a href="<?= SITE_URL ?>/iletisim" class="btn btn-outline">İletişime Geç</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

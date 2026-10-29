<?php
require_once __DIR__ . '/config/config.php';

$slug = clean($_GET['slug'] ?? '');
$db = get_db();

$stmt = $db->prepare("SELECT * FROM blog_posts WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: " . SITE_URL . "/blog");
    exit;
}

// Okunma sayısını artır
$db->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?")->execute([$post['id']]);

$pageTitle = $post['title'] . ' — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = $post['summary'];
$canonicalUrl = SITE_URL . '/blog/' . $post['slug'];
$ogImage = SITE_URL . '/' . ($post['cover_image'] ?: 'assets/images/therapy.jpg');
$ogType = 'article';

// Benzer Diğer Yazılar
$stmtRel = $db->prepare("SELECT * FROM blog_posts WHERE id != ? AND status = 'published' ORDER BY id DESC LIMIT 2");
$stmtRel->execute([$post['id']]);
$relatedPosts = $stmtRel->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<article class="container" style="padding: 60px 24px 100px; max-width: 860px;">
    
    <div style="margin-bottom: 24px;">
        <a href="<?= SITE_URL ?>/blog" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; color: var(--text-muted);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
            <span>Tüm Makalelere Dön</span>
        </a>
    </div>

    <!-- Başlık & Meta -->
    <header style="margin-bottom: 32px;">
        <span class="blog-category" style="font-size: 0.85rem;"><?= htmlspecialchars($post['category']) ?></span>
        <h1 style="font-size: 2.8rem; margin: 12px 0 16px; line-height: 1.25;"><?= htmlspecialchars($post['title']) ?></h1>
        
        <div style="display: flex; align-items: center; gap: 20px; font-size: 0.9rem; color: var(--text-muted); border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
            <span>Yazar: <strong><?= htmlspecialchars(get_setting('counselor_name')) ?></strong></span>
            <span>•</span>
            <span><?= format_date_tr($post['published_at'] ?: $post['created_at']) ?></span>
            <span>•</span>
            <span>⏱ <?= htmlspecialchars($post['reading_time']) ?> okuma</span>
            <span>•</span>
            <span>👁 <?= (int)$post['views'] ?> görüntülenme</span>
        </div>
    </header>

    <!-- Öne Çıkan Görsel -->
    <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 40px; box-shadow: var(--shadow-md);">
        <img src="<?= SITE_URL ?>/<?= htmlspecialchars($post['cover_image'] ?: 'assets/images/therapy.jpg') ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; max-height: 440px; object-fit: cover;">
    </div>

    <!-- Yazı İçeriği -->
    <div class="blog-content" style="font-size: 1.125rem; line-height: 1.85; color: var(--text-primary);">
        <?= $post['content'] ?>
    </div>

    <!-- Yazar Kartı & Paylaşım -->
    <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 30px; margin: 60px 0 40px; display: flex; gap: 24px; align-items: center;">
        <div style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
            <img src="<?= SITE_URL ?>/assets/images/counselor.jpg" alt="<?= htmlspecialchars(get_setting('counselor_name')) ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
            <h4 style="font-size: 1.15rem; margin-bottom: 6px;"><?= htmlspecialchars(get_setting('counselor_name')) ?></h4>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 12px;">
                <?= htmlspecialchars(get_setting('counselor_title')) ?>
            </p>
            <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary" style="padding: 6px 18px; font-size: 0.85rem;">Randevu Al</a>
        </div>
    </div>

    <!-- Benzer Yazılar -->
    <?php if (!empty($relatedPosts)): ?>
    <div style="margin-top: 50px;">
        <h3 style="font-size: 1.5rem; margin-bottom: 24px;">Diğer Faydalı Yazılar</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            <?php foreach ($relatedPosts as $rel): ?>
            <div class="blog-card">
                <a href="<?= SITE_URL ?>/blog/<?= urlencode($rel['slug']) ?>" class="blog-thumb" style="height: 150px;">
                    <img src="<?= SITE_URL ?>/<?= htmlspecialchars($rel['cover_image'] ?: 'assets/images/therapy.jpg') ?>" alt="<?= htmlspecialchars($rel['title']) ?>">
                </a>
                <div class="blog-body" style="padding: 16px;">
                    <span class="blog-category"><?= htmlspecialchars($rel['category']) ?></span>
                    <h4 style="font-size: 1.05rem; margin-bottom: 8px;">
                        <a href="<?= SITE_URL ?>/blog/<?= urlencode($rel['slug']) ?>">
                            <?= htmlspecialchars($rel['title']) ?>
                        </a>
                    </h4>
                    <span style="font-size: 0.8rem; color: var(--text-muted);"><?= format_date_tr($rel['published_at'] ?: $rel['created_at']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</article>

<?php include __DIR__ . '/includes/footer.php'; ?>

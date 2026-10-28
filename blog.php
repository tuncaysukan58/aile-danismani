<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Blog & Yazılar — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = 'Evlilik, çift ilişkileri, ebeveynlik ve çocuk gelişimi üzerine güncel rehber makaleler.';

$db = get_db();

// Kategori & Arama
$category = clean($_GET['kat'] ?? '');
$search = clean($_GET['q'] ?? '');

$sql = "SELECT * FROM blog_posts WHERE status = 'published'";
$params = [];

if ($category) {
    $sql .= " AND category = ?";
    $params[] = $category;
}
if ($search) {
    $sql .= " AND (title LIKE ? OR summary LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

// Kategoriler
$stmtCat = $db->query("SELECT DISTINCT category FROM blog_posts WHERE status = 'published'");
$categories = $stmtCat->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px;">
    
    <div class="section-header">
        <span class="eyebrow">Yazılarım & Makaleler</span>
        <h1>Blog ve Rehber Yazılar</h1>
        <p>İlişkiler, evlilik, çocuk eğitimi ve aile psikolojisi üzerine kaleme aldığım bilimsel ve pratik rehberler.</p>
    </div>

    <!-- Filtre & Arama Çubuğu -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="<?= SITE_URL ?>/blog" class="btn <?= empty($category) ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 8px 18px; font-size: 0.85rem;">
                Tümü
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= SITE_URL ?>/blog?kat=<?= urlencode($cat) ?>" class="btn <?= $category === $cat ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 8px 18px; font-size: 0.85rem;">
                <?= htmlspecialchars($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <form action="<?= SITE_URL ?>/blog" method="GET" style="display: flex; gap: 8px; max-width: 300px; width: 100%;">
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Yazılarda ara..." class="form-control" style="padding: 8px 14px; font-size: 0.9rem;">
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px;">Ara</button>
        </form>
    </div>

    <!-- Blog Kartları Grid -->
    <div class="blog-grid">
        <?php if (empty($posts)): ?>
            <div style="grid-column: span 3; text-align: center; padding: 50px; color: var(--text-muted);">
                Aramanıza uygun yazı bulunamadı.
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
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
                        <span>⏱ <?= htmlspecialchars($post['reading_time']) ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

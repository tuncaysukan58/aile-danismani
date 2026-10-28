<?php
$adminTitle = 'Blog & Makale Yönetimi';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();
$message = '';
$error = '';

// Silme
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->execute([$delId]);
    $message = 'Makale silindi.';
}

// Durum Değiştirme (Taslak / Yayında)
if (isset($_GET['toggle'])) {
    $togId = (int)$_GET['toggle'];
    $stmt = $db->prepare("UPDATE blog_posts SET status = CASE WHEN status = 'published' THEN 'draft' ELSE 'published' END WHERE id = ?");
    $stmt->execute([$togId]);
    $message = 'Makale yayın durumu güncellendi.';
}

$editId = (int)($_GET['edit'] ?? 0);
$editPost = null;

if ($editId > 0) {
    $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$editId]);
    $editPost = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean($_POST['title'] ?? '');
    $category = clean($_POST['category'] ?? 'Aile & İletişim');
    $summary = clean($_POST['summary'] ?? '');
    $content = $_POST['content'] ?? ''; // HTML içerik
    $readingTime = clean($_POST['reading_time'] ?? '4 dk');
    $status = isset($_POST['is_published']) ? 'published' : 'draft';
    $slug = slugify($title);

    if ($title && $summary && $content) {
        if ($editPost) {
            $stmt = $db->prepare("
                UPDATE blog_posts 
                SET title = ?, slug = ?, category = ?, summary = ?, content = ?, reading_time = ?, status = ? 
                WHERE id = ?
            ");
            $stmt->execute([$title, $slug, $category, $summary, $content, $readingTime, $status, $editPost['id']]);
            $message = 'Makale başarıyla güncellendi.';
            $editPost = null;
        } else {
            $publishedAt = date('Y-m-d');
            $stmt = $db->prepare("
                INSERT INTO blog_posts (title, slug, category, summary, content, cover_image, reading_time, status, published_at) 
                VALUES (?, ?, ?, ?, ?, 'assets/images/therapy.jpg', ?, ?, ?)
            ");
            $stmt->execute([$title, $slug, $category, $summary, $content, $readingTime, $status, $publishedAt]);
            $message = 'Yeni makale yayınlandı.';
        }
    } else {
        $error = 'Lütfen başlık, özet ve içerik alanlarını eksiksiz doldurunuz.';
    }
}

// Tüm Makaleler
$posts = $db->query("SELECT * FROM blog_posts ORDER BY id DESC")->fetchAll();
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="admin-alert admin-alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 30px; align-items: flex-start;">
    
    <!-- Ekle / Düzenle Formu -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><?= $editPost ? 'Makaleyi Düzenle' : 'Yeni Makale Yaz' ?></h3>
            <?php if ($editPost): ?>
            <a href="<?= ADMIN_URL ?>/blog.php" class="btn-sm btn-secondary-sm">İptal</a>
            <?php endif; ?>
        </div>

        <form method="POST" action="">
            <div class="admin-form-group">
                <label for="title">Makale Başlığı *</label>
                <input type="text" id="title" name="title" class="admin-form-control" value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" required placeholder="Örn: Evlilikte İletişim Becerileri">
            </div>

            <div class="admin-form-group">
                <label for="category">Kategori</label>
                <input type="text" id="category" name="category" class="admin-form-control" value="<?= htmlspecialchars($editPost['category'] ?? 'Aile & İletişim') ?>" placeholder="Örn: Evlilik Terapisi, Çocuk & Ebeveyn">
            </div>

            <div class="admin-form-group">
                <label for="reading_time">Okuma Süresi</label>
                <input type="text" id="reading_time" name="reading_time" class="admin-form-control" value="<?= htmlspecialchars($editPost['reading_time'] ?? '4 dk') ?>" placeholder="Örn: 5 dk">
            </div>

            <div class="admin-form-group">
                <label for="summary">Kısa Özet *</label>
                <textarea id="summary" name="summary" class="admin-form-control" style="min-height: 80px;" required placeholder="Arama motorları ve kartlar için özet..."><?= htmlspecialchars($editPost['summary'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label for="content">Makale İçeriği (HTML Destekli) *</label>
                <textarea id="content" name="content" class="admin-form-control" style="min-height: 220px;" required placeholder="Makalenizi buraya yazınız... Paragraflar için <p>, alt başlıklar için <h3> kullanabilirsiniz."><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1" <?= (!isset($editPost) || $editPost['status'] === 'published') ? 'checked' : '' ?>>
                    <span>Hemen Yayına Al</span>
                </label>
            </div>

            <button type="submit" class="btn-sm btn-primary-sm" style="padding:10px 20px; width:100%; justify-content:center;">
                <?= $editPost ? 'Makaleyi Güncelle' : 'Makaleyi Kaydet & Yayınla' ?>
            </button>
        </form>
    </div>

    <!-- Mevcut Makaleler Listesi -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Yayınlanan Makaleler (<?= count($posts) ?>)</h3>
        </div>

        <?php if (empty($posts)): ?>
            <p style="text-align: center; color: var(--admin-text-muted); padding: 30px;">Henüz makale bulunmuyor.</p>
        <?php else: ?>
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Kategori</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $p): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($p['title']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($p['category']) ?></td>
                            <td><?= format_date_tr($p['published_at'] ?: $p['created_at']) ?></td>
                            <td>
                                <a href="?toggle=<?= $p['id'] ?>" class="badge <?= $p['status'] === 'published' ? 'badge-confirmed' : 'badge-pending' ?>">
                                    <?= $p['status'] === 'published' ? 'Yayında' : 'Taslak' ?>
                                </a>
                            </td>
                            <td>
                                <div style="display:flex; gap:6px;">
                                    <a href="<?= SITE_URL ?>/blog/<?= urlencode($p['slug']) ?>" target="_blank" class="btn-sm btn-secondary-sm" title="Önizle">Gör</a>
                                    <a href="?edit=<?= $p['id'] ?>" class="btn-sm btn-primary-sm">Düzenle</a>
                                    <a href="?delete=<?= $p['id'] ?>" class="btn-sm btn-secondary-sm" style="color:#EF4444;" onclick="return confirm('Bu makaleyi silmek istediğinize emin misiniz?');">Sil</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

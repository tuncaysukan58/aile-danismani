<?php
$adminTitle = 'Eğitimler ve Hizmetler';
require_once __DIR__ . '/includes/admin-header.php';
require_once __DIR__ . '/includes/image-upload.php';

const DEFAULT_SERVICE_IMAGE = 'assets/images/seminar.jpg';

$db = get_db();
$message = '';
$error = '';

// Silme İşlemi
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$delId]);
    $message = 'Eğitim / Hizmet başarıyla silindi.';
}

// Durum Değiştirme (Aktif / Pasif)
if (isset($_GET['toggle'])) {
    $togId = (int)$_GET['toggle'];
    $stmt = $db->prepare("UPDATE services SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?");
    $stmt->execute([$togId]);
    $message = 'Yayın durumu güncellendi.';
}

// Yeni Ekleme veya Düzenleme
$editId = (int)($_GET['edit'] ?? 0);
$editItem = null;

if ($editId > 0) {
    $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$editId]);
    $editItem = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean($_POST['title'] ?? '');
    $category = clean($_POST['category'] ?? 'Danışmanlık');
    $summary = clean($_POST['summary'] ?? '');
    $content = clean($_POST['content'] ?? '');
    $duration = clean($_POST['duration'] ?? '50 Dakika');
    $price = clean($_POST['price'] ?? 'Yüz Yüze / Online');
    $displayOrder = (int)($_POST['display_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $slug = slugify($title);

    // Görsel: 1) yeni yükleme  2) galeriden seçim  3) mevcut görsel  4) varsayılan
    $img = resolve_image_from_form($editItem['image'] ?? '', DEFAULT_SERVICE_IMAGE, 'services');
    $image = $img['image'];
    $error = $img['error'];

    if ($error) {
        // Görsel hatası: kaydetme, formu koru
    } elseif ($title && $summary) {
        if ($editItem) {
            // Güncelleme
            $stmt = $db->prepare("
                UPDATE services 
                SET title = ?, slug = ?, category = ?, summary = ?, content = ?, duration = ?, price = ?, display_order = ?, is_active = ?, image = ? 
                WHERE id = ?
            ");
            $stmt->execute([$title, $slug, $category, $summary, $content, $duration, $price, $displayOrder, $isActive, $image, $editItem['id']]);
            $message = 'Hizmet / Eğitim güncellendi.';
            $editItem = null;
        } else {
            // Yeni Ekleme
            $stmt = $db->prepare("
                INSERT INTO services (title, slug, category, summary, content, duration, price, display_order, is_active, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $slug, $category, $summary, $content, $duration, $price, $displayOrder, $isActive, $image]);
            $message = 'Yeni eğitim / danışmanlık hizmeti eklendi.';
        }
    } else {
        $error = 'Lütfen başlık ve özet alanlarını doldurunuz.';
    }
}

// Tüm Hizmetler
$services = $db->query("SELECT * FROM services ORDER BY display_order ASC, id DESC")->fetchAll();
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="admin-alert admin-alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; align-items: flex-start;">
    
    <!-- Ekle / Düzenle Formu -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3><?= $editItem ? 'Hizmeti Düzenle' : 'Yeni Eğitim / Hizmet Ekle' ?></h3>
            <?php if ($editItem): ?>
            <a href="<?= ADMIN_URL ?>/egitimler.php" class="btn-sm btn-secondary-sm">İptal</a>
            <?php endif; ?>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="admin-form-group">
                <label for="title">Başlık *</label>
                <input type="text" id="title" name="title" class="admin-form-control" value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" required placeholder="Örn: Çift ve Evlilik Terapisi">
            </div>

            <!-- Görsel Seçimi -->
            <?php render_image_picker($editItem['image'] ?? '', DEFAULT_SERVICE_IMAGE); ?>

            <div class="admin-form-group">
                <label for="category">Kategori</label>
                <select id="category" name="category" class="admin-form-control">
                    <option value="Danışmanlık" <?= ($editItem['category'] ?? '') === 'Danışmanlık' ? 'selected' : '' ?>>Danışmanlık (Bireysel / Çift)</option>
                    <option value="Eğitim & Seminer" <?= ($editItem['category'] ?? '') === 'Eğitim & Seminer' ? 'selected' : '' ?>>Eğitim & Seminer</option>
                    <option value="Atölye" <?= ($editItem['category'] ?? '') === 'Atölye' ? 'selected' : '' ?>>Atölye Çalışması</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label for="duration">Süre</label>
                <input type="text" id="duration" name="duration" class="admin-form-control" value="<?= htmlspecialchars($editItem['duration'] ?? '50 Dakika') ?>" placeholder="Örn: 50 Dakika veya 4 Hafta">
            </div>

            <div class="admin-form-group">
                <label for="price">Format / Ücret Bilgisi</label>
                <input type="text" id="price" name="price" class="admin-form-control" value="<?= htmlspecialchars($editItem['price'] ?? 'Yüz Yüze / Online') ?>" placeholder="Örn: Yüz Yüze / Online">
            </div>

            <div class="admin-form-group">
                <label for="display_order">Sıralama</label>
                <input type="number" id="display_order" name="display_order" class="admin-form-control" value="<?= (int)($editItem['display_order'] ?? 0) ?>">
            </div>

            <div class="admin-form-group">
                <label for="summary">Kısa Özet *</label>
                <textarea id="summary" name="summary" class="admin-form-control" style="min-height: 80px;" required placeholder="Kartta görünecek kısa açıklama..."><?= htmlspecialchars($editItem['summary'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label for="content">Detaylı Açıklama / İçerik</label>
                <textarea id="content" name="content" class="admin-form-control" style="min-height: 140px;" placeholder="Programın tam içeriği, kazanımları ve detayları..."><?= htmlspecialchars($editItem['content'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= (!isset($editItem) || $editItem['is_active']) ? 'checked' : '' ?>>
                    <span>Sitede Yayında Olsun</span>
                </label>
            </div>

            <button type="submit" class="btn-sm btn-primary-sm" style="padding:10px 20px; width:100%; justify-content:center;">
                <?= $editItem ? 'Değişiklikleri Kaydet' : 'Eğitimi / Hizmeti Ekle' ?>
            </button>
        </form>
    </div>

    <!-- Mevcut Hizmetler Listesi -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Mevcut Eğitim ve Hizmetler (<?= count($services) ?>)</h3>
        </div>

        <?php if (empty($services)): ?>
            <p style="text-align: center; color: var(--admin-text-muted); padding: 30px;">Henüz hizmet eklenmemiş.</p>
        <?php else: ?>
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Görsel</th>
                            <th>Başlık</th>
                            <th>Kategori</th>
                            <th>Süre</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $svc): ?>
                        <tr>
                            <td><?= $svc['display_order'] ?></td>
                            <td>
                                <a href="<?= SITE_URL ?>/<?= htmlspecialchars($svc['image'] ?: DEFAULT_SERVICE_IMAGE) ?>" target="_blank" title="Görseli büyük aç">
                                    <img class="svc-thumb" src="<?= SITE_URL ?>/<?= htmlspecialchars($svc['image'] ?: DEFAULT_SERVICE_IMAGE) ?>" alt="" loading="lazy">
                                </a>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($svc['title']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($svc['category']) ?></td>
                            <td><?= htmlspecialchars($svc['duration']) ?></td>
                            <td>
                                <a href="?toggle=<?= $svc['id'] ?>" class="badge <?= $svc['is_active'] ? 'badge-confirmed' : 'badge-cancelled' ?>" title="Durumu değiştirmek için tıklayın">
                                    <?= $svc['is_active'] ? 'Yayında' : 'Pasif' ?>
                                </a>
                            </td>
                            <td>
                                <div style="display:flex; gap:6px;">
                                    <a href="?edit=<?= $svc['id'] ?>" class="btn-sm btn-primary-sm">Düzenle</a>
                                    <a href="?delete=<?= $svc['id'] ?>" class="btn-sm btn-secondary-sm" style="color:#EF4444;" onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?');">Sil</a>
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

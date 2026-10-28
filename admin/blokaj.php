<?php
$adminTitle = 'Tarih & Saat Kapatma (Blokaj)';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();
$message = '';
$error = '';

// Blokaj Ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_block'])) {
    $blockDate = clean($_POST['block_date'] ?? '');
    $blockTime = clean($_POST['block_time'] ?? ''); // Boşsa tüm gün
    $reason = clean($_POST['reason'] ?? '');

    if ($blockDate) {
        $stmt = $db->prepare("INSERT INTO blocked_slots (block_date, block_time, reason) VALUES (?, ?, ?)");
        if ($stmt->execute([$blockDate, $blockTime ?: null, $reason])) {
            $message = 'Tarih / Saat başarıyla randevuya kapatıldı.';
        } else {
            $error = 'Kapatma işlemi sırasında hata oluştu.';
        }
    } else {
        $error = 'Lütfen en az bir tarih seçiniz.';
    }
}

// Blokaj Kaldırma
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM blocked_slots WHERE id = ?");
    $stmt->execute([$deleteId]);
    $message = 'Blokaj kaldırıldı, tarih/saat tekrar randevuya açıldı.';
}

// Mevcut Blokajlar
$blockedSlots = $db->query("SELECT * FROM blocked_slots ORDER BY block_date ASC")->fetchAll();

// Sistemdeki randevu saatleri
$rawSlots = get_setting('available_slots', '10:00,11:15,13:00,14:15,15:30,16:45,18:00');
$availableSlots = array_filter(array_map('trim', explode(',', $rawSlots)));
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="admin-alert admin-alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 0.8fr 1.2fr; gap: 30px; align-items: flex-start;">
    
    <!-- Blokaj Ekle Formu -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Randevuya Kapat (İzin / Tatil)</h3>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="add_block" value="1">

            <div class="admin-form-group">
                <label for="block_date">Kapatılacak Tarih *</label>
                <input type="date" id="block_date" name="block_date" class="admin-form-control" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="admin-form-group">
                <label for="block_time">Saat Dilimi (Opsiyonel)</label>
                <select id="block_time" name="block_time" class="admin-form-control">
                    <option value="">-- Tüm Gün Kapalı (Tatil / İzin) --</option>
                    <?php foreach ($availableSlots as $slot): ?>
                    <option value="<?= htmlspecialchars($slot) ?>">Sadece <?= htmlspecialchars($slot) ?> Saati</option>
                    <?php endforeach; ?>
                </select>
                <small style="color:var(--admin-text-muted); font-size:0.8rem; display:block; margin-top:4px;">
                    Eğer tüm günü kapatmak istiyorsanız bu alanı boş bırakınız.
                </small>
            </div>

            <div class="admin-form-group">
                <label for="reason">Kapatma Nedeni / Açıklama (Opsiyonel)</label>
                <input type="text" id="reason" name="reason" class="admin-form-control" placeholder="Örn: Seminer Eğitimi, Şehir Dışı İzni, Resmi Tatil">
            </div>

            <button type="submit" class="btn-sm btn-primary-sm" style="padding:10px 20px; width:100%; justify-content:center;">
                Tarihi / Saati Randevuya Kapat
            </button>
        </form>
    </div>

    <!-- Aktif Kapalı Tarihler Listesi -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Kapalı Olan Tarih ve Saatler (<?= count($blockedSlots) ?>)</h3>
        </div>

        <?php if (empty($blockedSlots)): ?>
            <p style="text-align: center; color: var(--admin-text-muted); padding: 30px;">Şu anda manuel olarak kapatılmış bir gün veya saat bulunmuyor.</p>
        <?php else: ?>
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Kapatılan Aralık</th>
                            <th>Açıklama</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blockedSlots as $block): ?>
                        <tr>
                            <td><strong><?= format_date_tr($block['block_date']) ?></strong></td>
                            <td>
                                <?php if ($block['block_time']): ?>
                                    <span class="badge badge-warning" style="background:#FEF3C7; color:#B45309;"><?= htmlspecialchars($block['block_time']) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Tüm Gün Kapalı</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($block['reason'] ?: '-') ?></td>
                            <td>
                                <a href="?delete=<?= $block['id'] ?>" class="btn-sm btn-secondary-sm" style="color:#EF4444;" onclick="return confirm('Bu blokajı kaldırıp tekrar randevuya açmak istediğinize emin misiniz?');">
                                    Aç (Sil)
                                </a>
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

<?php
$adminTitle = 'Randevu Yönetimi';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();
$message = '';

// Durum Güncelleme veya Silme İşlemleri
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if (in_array($action, ['pending', 'confirmed', 'cancelled', 'completed'])) {
        $stmt = $db->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->execute([$action, $id]);
        $message = 'Randevu durumu güncellendi.';
    } elseif ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Randevu silindi.';
    }
}

// Filtreleme
$statusFilter = clean($_GET['status'] ?? '');
$sql = "
    SELECT a.*, s.title as service_title 
    FROM appointments a 
    LEFT JOIN services s ON a.service_id = s.id 
";
$params = [];
if ($statusFilter) {
    $sql .= " WHERE a.status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Gelen Tüm Randevular (<?= count($appointments) ?>)</h3>
        
        <div style="display:flex; gap:8px;">
            <a href="<?= ADMIN_URL ?>/randevular.php" class="btn-sm <?= empty($statusFilter) ? 'btn-primary-sm' : 'btn-secondary-sm' ?>">Tümü</a>
            <a href="<?= ADMIN_URL ?>/randevular.php?status=pending" class="btn-sm <?= $statusFilter === 'pending' ? 'btn-primary-sm' : 'btn-secondary-sm' ?>">Bekleyen</a>
            <a href="<?= ADMIN_URL ?>/randevular.php?status=confirmed" class="btn-sm <?= $statusFilter === 'confirmed' ? 'btn-primary-sm' : 'btn-secondary-sm' ?>">Onaylanan</a>
            <a href="<?= ADMIN_URL ?>/randevular.php?status=cancelled" class="btn-sm <?= $statusFilter === 'cancelled' ? 'btn-primary-sm' : 'btn-secondary-sm' ?>">İptal Edilen</a>
            <a href="<?= ADMIN_URL ?>/randevular.php?status=completed" class="btn-sm <?= $statusFilter === 'completed' ? 'btn-primary-sm' : 'btn-secondary-sm' ?>">Tamamlanan</a>
        </div>
    </div>

    <?php if (empty($appointments)): ?>
        <p style="text-align: center; color: var(--admin-text-muted); padding: 30px;">Seçilen kritere uygun randevu bulunamadı.</p>
    <?php else: ?>
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tarih & Saat</th>
                        <th>Danışan</th>
                        <th>İletişim</th>
                        <th>Hizmet</th>
                        <th>Danışan Notu</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $app): 
                        $statusClass = 'badge-pending';
                        $statusText = 'Beklemede';
                        if ($app['status'] === 'confirmed') { $statusClass = 'badge-confirmed'; $statusText = 'Onaylandı'; }
                        elseif ($app['status'] === 'cancelled') { $statusClass = 'badge-cancelled'; $statusText = 'İptal'; }
                        elseif ($app['status'] === 'completed') { $statusClass = 'badge-completed'; $statusText = 'Tamamlandı'; }

                        $cleanPhone = preg_replace('/[^0-9]/', '', $app['client_phone']);
                        if (substr($cleanPhone, 0, 1) === '0') $cleanPhone = '90' . substr($cleanPhone, 1);
                        $waText = urlencode("Merhaba Sayın " . $app['client_name'] . ", " . format_date_tr($app['appointment_date']) . " saat " . $app['appointment_time'] . " tarihindeki aile danışmanlığı randevunuz onaylanmıştır.");
                    ?>
                    <tr>
                        <td>#<?= $app['id'] ?></td>
                        <td>
                            <strong><?= format_date_tr($app['appointment_date']) ?></strong><br>
                            <span style="color:var(--primary); font-weight:600;"><?= htmlspecialchars($app['appointment_time']) ?></span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($app['client_name']) ?></strong>
                        </td>
                        <td>
                            <div>📞 <?= htmlspecialchars($app['client_phone']) ?></div>
                            <?php if ($app['client_email']): ?>
                            <small style="color:var(--admin-text-muted);">✉️ <?= htmlspecialchars($app['client_email']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($app['service_title'] ?: 'Genel Danışmanlık') ?></td>
                        <td style="max-width:200px; font-size:0.8rem; color:var(--admin-text-muted);">
                            <?= nl2br(htmlspecialchars($app['notes'] ?: '-')) ?>
                        </td>
                        <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                        <td>
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                <a href="https://api.whatsapp.com/send?phone=<?= $cleanPhone ?>&text=<?= $waText ?>" target="_blank" class="btn-sm btn-whatsapp-sm">
                                    WhatsApp İle Yaz
                                </a>

                                <div style="display:flex; gap:4px;">
                                    <?php if ($app['status'] !== 'confirmed'): ?>
                                    <a href="?action=confirmed&id=<?= $app['id'] ?>" class="btn-sm btn-primary-sm" title="Randevuyu Onayla">Onayla</a>
                                    <?php endif; ?>

                                    <?php if ($app['status'] !== 'completed'): ?>
                                    <a href="?action=completed&id=<?= $app['id'] ?>" class="btn-sm btn-secondary-sm" title="Seans Tamamlandı">Tamamla</a>
                                    <?php endif; ?>

                                    <?php if ($app['status'] !== 'cancelled'): ?>
                                    <a href="?action=cancelled&id=<?= $app['id'] ?>" class="btn-sm btn-danger-sm" title="Randevuyu İptal Et">İptal</a>
                                    <?php endif; ?>

                                    <a href="?action=delete&id=<?= $app['id'] ?>" class="btn-sm btn-secondary-sm" style="color:#EF4444;" onclick="return confirm('Bu randevuyu kalıcı olarak silmek istediğinize emin misiniz?');">Sil</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

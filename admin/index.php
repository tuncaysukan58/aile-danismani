<?php
$adminTitle = 'Gösterge Paneli';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();

// İstatistikler
$totalAppointments = $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$pendingAppointments = $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();
$totalServices = $db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
$unreadMessages = $db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();

// Yaklaşan / Son Randevular (5 adet)
$stmt = $db->query("
    SELECT a.*, s.title as service_title 
    FROM appointments a 
    LEFT JOIN services s ON a.service_id = s.id 
    ORDER BY a.appointment_date DESC, a.appointment_time DESC 
    LIMIT 5
");
$recentAppointments = $stmt->fetchAll();

// Son Gelen Mesajlar (3 adet)
$stmtMsg = $db->query("SELECT * FROM messages ORDER BY id DESC LIMIT 3");
$recentMessages = $stmtMsg->fetchAll();
?>

<!-- 4'lü İstatistik Kartları -->
<div class="stat-cards-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div class="stat-info">
            <h3><?= (int)$totalAppointments ?></h3>
            <span>Toplam Randevu</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="stat-info">
            <h3><?= (int)$pendingAppointments ?></h3>
            <span>Onay Bekleyen Randevu</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        </div>
        <div class="stat-info">
            <h3><?= (int)$totalServices ?></h3>
            <span>Aktif Hizmet / Eğitim</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div class="stat-info">
            <h3><?= (int)$unreadMessages ?></h3>
            <span>Okunmamış Mesaj</span>
        </div>
    </div>
</div>

<!-- Son Randevular Tablosu -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Son Gelen Randevu Talepleri</h3>
        <a href="<?= ADMIN_URL ?>/randevular.php" class="btn-sm btn-primary-sm">Tümünü Yönet</a>
    </div>

    <?php if (empty($recentAppointments)): ?>
        <p style="color: var(--admin-text-muted); text-align: center; padding: 20px;">Henüz kayıtlı randevu bulunmuyor.</p>
    <?php else: ?>
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tarih & Saat</th>
                        <th>Danışan</th>
                        <th>Telefon</th>
                        <th>Hizmet</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAppointments as $app): 
                        $statusClass = 'badge-pending';
                        $statusText = 'Beklemede';
                        if ($app['status'] === 'confirmed') { $statusClass = 'badge-confirmed'; $statusText = 'Onaylandı'; }
                        elseif ($app['status'] === 'cancelled') { $statusClass = 'badge-cancelled'; $statusText = 'İptal'; }
                        elseif ($app['status'] === 'completed') { $statusClass = 'badge-completed'; $statusText = 'Tamamlandı'; }
                        
                        $cleanPhone = preg_replace('/[^0-9]/', '', $app['client_phone']);
                        if (substr($cleanPhone, 0, 1) === '0') $cleanPhone = '90' . substr($cleanPhone, 1);
                        $waText = urlencode("Merhaba Sayın " . $app['client_name'] . ", " . format_date_tr($app['appointment_date']) . " saat " . $app['appointment_time'] . " tarihindeki randevunuz ile ilgili bilgi vermek istiyorum.");
                    ?>
                    <tr>
                        <td>
                            <strong><?= format_date_tr($app['appointment_date']) ?></strong><br>
                            <small style="color:var(--admin-text-muted);"><?= htmlspecialchars($app['appointment_time']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($app['client_name']) ?></td>
                        <td><?= htmlspecialchars($app['client_phone']) ?></td>
                        <td><?= htmlspecialchars($app['service_title'] ?: 'Genel Danışmanlık') ?></td>
                        <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="https://api.whatsapp.com/send?phone=<?= $cleanPhone ?>&text=<?= $waText ?>" target="_blank" class="btn-sm btn-whatsapp-sm" title="Danışana WhatsApp'tan Yaz">
                                    WhatsApp
                                </a>
                                <a href="<?= ADMIN_URL ?>/randevular.php" class="btn-sm btn-secondary-sm">
                                    Detay
                                </a>
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

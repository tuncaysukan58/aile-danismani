<?php
$adminTitle = 'İletişim Mesajları';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();
$message = '';

// Okundu Olarak İşaretle
if (isset($_GET['read'])) {
    $readId = (int)$_GET['read'];
    $stmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$readId]);
    $message = 'Mesaj okundu olarak işaretlendi.';
}

// Sil
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$delId]);
    $message = 'Mesaj silindi.';
}

$messages = $db->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Web Sitesinden Gelen Mesajlar (<?= count($messages) ?>)</h3>
    </div>

    <?php if (empty($messages)): ?>
        <p style="text-align: center; color: var(--admin-text-muted); padding: 30px;">Henüz gelen bir iletişim mesajı bulunmuyor.</p>
    <?php else: ?>
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Gönderen</th>
                        <th>İletişim</th>
                        <th>Konu & Mesaj</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): 
                        $cleanPhone = preg_replace('/[^0-9]/', '', $msg['phone']);
                        if (substr($cleanPhone, 0, 1) === '0') $cleanPhone = '90' . substr($cleanPhone, 1);
                    ?>
                    <tr style="<?= $msg['is_read'] ? '' : 'background: #FFFBEB;' ?>">
                        <td><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></td>
                        <td><strong><?= htmlspecialchars($msg['name']) ?></strong></td>
                        <td>
                            <div>✉️ <?= htmlspecialchars($msg['email']) ?></div>
                            <?php if ($msg['phone']): ?>
                            <small>📞 <?= htmlspecialchars($msg['phone']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:350px;">
                            <?php if ($msg['subject']): ?>
                            <div style="font-weight:600; margin-bottom:4px;"><?= htmlspecialchars($msg['subject']) ?></div>
                            <?php endif; ?>
                            <div style="font-size:0.875rem; color:var(--admin-text-main);"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                        </td>
                        <td>
                            <span class="badge <?= $msg['is_read'] ? 'badge-completed' : 'badge-pending' ?>">
                                <?= $msg['is_read'] ? 'Okundu' : 'Yeni Mesaj' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <?php if ($cleanPhone): ?>
                                <a href="https://api.whatsapp.com/send?phone=<?= $cleanPhone ?>" target="_blank" class="btn-sm btn-whatsapp-sm">WhatsApp</a>
                                <?php endif; ?>

                                <?php if (!$msg['is_read']): ?>
                                <a href="?read=<?= $msg['id'] ?>" class="btn-sm btn-secondary-sm">Okundu Yap</a>
                                <?php endif; ?>

                                <a href="?delete=<?= $msg['id'] ?>" class="btn-sm btn-secondary-sm" style="color:#EF4444;" onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?');">Sil</a>
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

<?php
$adminTitle = 'Site Ayarları & Danışman Bilgileri';
require_once __DIR__ . '/includes/admin-header.php';

$db = get_db();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        $settingsList = [
            'site_title', 'counselor_name', 'counselor_title', 'eyebrow', 'hero_mission',
            'whatsapp_number', 'phone', 'email', 'address', 'working_hours', 'available_slots',
            'about_text_short', 'about_text_full', 'stat_experience', 'stat_clients',
            'stat_seminars', 'stat_followers', 'instagram', 'youtube', 'linkedin'
        ];

        foreach ($settingsList as $key) {
            if (isset($_POST[$key])) {
                set_setting($key, trim($_POST[$key]));
            }
        }
        $message = 'Site ayarları başarıyla kaydedildi!';
    }

    if (isset($_POST['change_password'])) {
        $currPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if ($newPass && $newPass === $confirmPass) {
            $stmt = $db->prepare("SELECT password FROM admins WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
            $adminUser = $stmt->fetch();

            if ($adminUser && password_verify($currPass, $adminUser['password'])) {
                $newHash = password_hash($newPass, PASSWORD_DEFAULT);
                $upd = $db->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $upd->execute([$newHash, $_SESSION['admin_id']]);
                $message = 'Yönetici şifreniz başarıyla değiştirildi!';
            } else {
                $error = 'Mevcut şifrenizi hatalı girdiniz.';
            }
        } else {
            $error = 'Yeni şifreler birbiriyle eşleşmiyor veya boş.';
        }
    }
}
?>

<?php if ($message): ?>
<div class="admin-alert admin-alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="admin-alert admin-alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 30px; align-items: flex-start;">
    
    <!-- Genel Ayarlar Formu -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Genel Bilgiler ve İletişim Ayarları</h3>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="save_settings" value="1">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="admin-form-group">
                    <label for="counselor_name">Danışman Adı Soyadı *</label>
                    <input type="text" id="counselor_name" name="counselor_name" class="admin-form-control" value="<?= htmlspecialchars(get_setting('counselor_name')) ?>" required>
                </div>
                <div class="admin-form-group">
                    <label for="counselor_title">Unvan *</label>
                    <input type="text" id="counselor_title" name="counselor_title" class="admin-form-control" value="<?= htmlspecialchars(get_setting('counselor_title')) ?>" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label for="site_title">Site Başlığı (Title & SEO)</label>
                <input type="text" id="site_title" name="site_title" class="admin-form-control" value="<?= htmlspecialchars(get_setting('site_title')) ?>">
            </div>

            <div class="admin-form-group">
                <label for="eyebrow">Anasayfa Üst Rozet Metni</label>
                <input type="text" id="eyebrow" name="eyebrow" class="admin-form-control" value="<?= htmlspecialchars(get_setting('eyebrow')) ?>">
            </div>

            <div class="admin-form-group">
                <label for="hero_mission">Anasayfa Misyon / Karşılama Metni</label>
                <textarea id="hero_mission" name="hero_mission" class="admin-form-control" style="min-height: 90px;"><?= htmlspecialchars(get_setting('hero_mission')) ?></textarea>
            </div>

            <h4 style="margin: 24px 0 16px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">İletişim & Randevu Bildirim Ayarları</h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="admin-form-group">
                    <label for="whatsapp_number" style="color:#059669;">WhatsApp Randevu Numarası * (Bildirimlerin Gideceği Hat)</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" class="admin-form-control" value="<?= htmlspecialchars(get_setting('whatsapp_number')) ?>" required placeholder="905551234567">
                    <small style="color:var(--admin-text-muted); font-size:0.75rem;">Örn: 905551234567 (Başında 90 olacak şekilde)</small>
                </div>
                <div class="admin-form-group">
                    <label for="phone">Telefon Numarası (Görünür Format)</label>
                    <input type="text" id="phone" name="phone" class="admin-form-control" value="<?= htmlspecialchars(get_setting('phone')) ?>" placeholder="+90 (555) 123 45 67">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="admin-form-group">
                    <label for="email">E-posta Adresi</label>
                    <input type="email" id="email" name="email" class="admin-form-control" value="<?= htmlspecialchars(get_setting('email')) ?>">
                </div>
                <div class="admin-form-group">
                    <label for="working_hours">Çalışma Saatleri</label>
                    <input type="text" id="working_hours" name="working_hours" class="admin-form-control" value="<?= htmlspecialchars(get_setting('working_hours')) ?>">
                </div>
            </div>

            <div class="admin-form-group">
                <label for="address">Ofis Adresi</label>
                <input type="text" id="address" name="address" class="admin-form-control" value="<?= htmlspecialchars(get_setting('address')) ?>">
            </div>

            <div class="admin-form-group">
                <label for="available_slots" style="color:var(--primary);">Randevu Saat Dilimleri (Virgülle Ayrılmış)</label>
                <input type="text" id="available_slots" name="available_slots" class="admin-form-control" value="<?= htmlspecialchars(get_setting('available_slots')) ?>" required>
                <small style="color:var(--admin-text-muted); font-size:0.75rem;">Randevu sayfasında görünecek seans saatleri: 10:00, 11:15, 13:00, 14:15, 15:30, 16:45, 18:00</small>
            </div>

            <h4 style="margin: 24px 0 16px; border-bottom: 1px solid var(--admin-border); padding-bottom: 8px;">Sosyal Medya Bağlantıları</h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                <div class="admin-form-group">
                    <label for="instagram">Instagram</label>
                    <input type="text" id="instagram" name="instagram" class="admin-form-control" value="<?= htmlspecialchars(get_setting('instagram')) ?>">
                </div>
                <div class="admin-form-group">
                    <label for="youtube">YouTube</label>
                    <input type="text" id="youtube" name="youtube" class="admin-form-control" value="<?= htmlspecialchars(get_setting('youtube')) ?>">
                </div>
                <div class="admin-form-group">
                    <label for="linkedin">LinkedIn</label>
                    <input type="text" id="linkedin" name="linkedin" class="admin-form-control" value="<?= htmlspecialchars(get_setting('linkedin')) ?>">
                </div>
            </div>

            <button type="submit" class="btn-sm btn-primary-sm" style="padding:12px 28px; font-size:1rem; margin-top:10px;">
                Ayarları Kaydet
            </button>
        </form>
    </div>

    <!-- Sağ Taraf: Şifre Değiştirme -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Yönetici Şifresini Değiştir</h3>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="change_password" value="1">

            <div class="admin-form-group">
                <label for="current_password">Mevcut Şifreniz *</label>
                <input type="password" id="current_password" name="current_password" class="admin-form-control" required placeholder="••••••••">
            </div>

            <div class="admin-form-group">
                <label for="new_password">Yeni Şifreniz *</label>
                <input type="password" id="new_password" name="new_password" class="admin-form-control" required placeholder="En az 6 karakter">
            </div>

            <div class="admin-form-group">
                <label for="confirm_password">Yeni Şifre (Tekrar) *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="admin-form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-sm btn-secondary-sm" style="width:100%; justify-content:center; padding:10px;">
                Şifreyi Güncelle
            </button>
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>

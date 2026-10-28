<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'İletişim & Konum — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = 'Aile danışmanlığı randevusu, e-posta ve doğrudan WhatsApp iletişim hattı.';

$db = get_db();
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $message = clean($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $db->prepare("INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
            $successMessage = 'Mesajınız başarıyla iletildi. En kısa sürede sizinle iletişime geçeceğiz.';
        } else {
            $errorMessage = 'Mesaj gönderilirken bir hata oluştu, lütfen tekrar deneyiniz.';
        }
    } else {
        $errorMessage = 'Lütfen zorunlu alanları (Ad, E-posta, Mesaj) doldurunuz.';
    }
}

$waUrl = get_whatsapp_url('Merhaba, danışmanlık süreci hakkında bilgi almak istiyorum.');
$waDisplay = get_whatsapp_display();

include __DIR__ . '/includes/header.php';
?>

<div class="contact-page-wrapper">
    <div class="container">
        
        <div class="section-header">
            <span class="eyebrow">Bağlantıda Kalalım</span>
            <h1>İletişim & Danışma</h1>
            <p>Danışmanlık seansları, seminer iş birlikleri veya aklınıza takılan her türlü soru için bize dilediğiniz kanaldan ulaşabilirsiniz.</p>
        </div>

        <?php if ($successMessage): ?>
        <div class="contact-alert contact-alert-success">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span><?= htmlspecialchars($successMessage) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
        <div class="contact-alert contact-alert-danger">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span><?= htmlspecialchars($errorMessage) ?></span>
        </div>
        <?php endif; ?>

        <div class="contact-main-grid">
            <!-- Sol Kolon: İletişim Detayları & WhatsApp -->
            <div class="contact-info-col">
                
                <!-- WhatsApp Canlı Danışma Kartı -->
                <div class="contact-wa-box">
                    <div class="contact-wa-header">
                        <div class="wa-icon-circle">
                            <svg viewBox="0 0 32 32" width="26" height="26" fill="currentColor">
                                <path d="M16 2.5C8.544 2.5 2.5 8.544 2.5 16c0 2.653.766 5.129 2.091 7.218L3 29.5l6.471-1.558A13.435 13.435 0 0 0 16 29.5c7.456 0 13.5-6.044 13.5-13.5S23.456 2.5 16 2.5zm0 24.582c-2.348 0-4.542-.693-6.386-1.892l-.458-.3-4.242 1.021 1.054-4.103-.314-.477A11.026 11.026 0 0 1 4.918 16c0-6.11 4.972-11.082 11.082-11.082 6.11 0 11.082 4.972 11.082 11.082 0 6.11-4.972 11.082-11.082 11.082zm6.079-8.314c-.334-.167-1.975-.975-2.281-1.086-.306-.111-.528-.167-.751.167-.223.334-.863 1.086-1.057 1.309-.195.223-.39.251-.724.084-.334-.167-1.411-.52-2.688-1.659-.993-.886-1.664-1.98-1.859-2.314-.195-.334-.021-.515.146-.681.151-.15.334-.39.501-.585.167-.195.223-.334.334-.557.111-.223.056-.418-.028-.585-.084-.167-.751-1.81-1.03-2.478-.271-.651-.547-.563-.751-.573l-.641-.01c-.223 0-.585.084-.891.418-.306.334-1.17 1.142-1.17 2.784 0 1.643 1.198 3.23 1.365 3.453.167.223 2.356 3.6 5.709 5.049.798.345 1.42.551 1.905.705.801.254 1.53.218 2.107.132.643-.096 1.975-.808 2.253-1.588.278-.78.278-1.448.195-1.588-.084-.139-.306-.223-.64-.39z"/>
                            </svg>
                        </div>
                        <div>
                            <h4>WhatsApp Danışma Hattı (<?= htmlspecialchars($waDisplay) ?>)</h4>
                            <p>En hızlı yanıt ve randevu teyidi için WhatsApp üzerinden doğrudan yazabilirsiniz.</p>
                        </div>
                    </div>
                    <a href="<?= $waUrl ?>" target="_blank" class="btn btn-wa-chat">
                        <span>WhatsApp ile Hemen Yazın</span>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- İletişim Bilgileri Kartı -->
                <div class="contact-details-card">
                    <h3 class="card-inner-title">İletişim Kanalları</h3>

                    <div class="contact-meta-list">
                        <div class="contact-meta-row">
                            <div class="meta-icon">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <div class="meta-text">
                                <label>WhatsApp İletişim</label>
                                <a href="<?= $waUrl ?>" target="_blank" style="color:var(--primary); font-weight:600; text-decoration:none;">
                                    <?= htmlspecialchars($waDisplay) ?>
                                </a>
                            </div>
                        </div>

                        <div class="contact-meta-row">
                            <div class="meta-icon">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </div>
                            <div class="meta-text">
                                <label>E-posta Adresi</label>
                                <a href="mailto:<?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?>" style="color:inherit; text-decoration:none;">
                                    <?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?>
                                </a>
                            </div>
                        </div>

                        <div class="contact-meta-row">
                            <div class="meta-icon">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <div class="meta-text">
                                <label>Çalışma Saatleri</label>
                                <span><?= htmlspecialchars(get_setting('working_hours', 'Pazartesi - Cumartesi: 10:00 - 18:30')) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sağ Kolon: Yenilenen Lüks Mesaj Formu -->
            <div class="contact-form-col">
                <div class="contact-form-card">
                    <div class="form-card-header">
                        <h3>Bize Mesaj Gönderin</h3>
                        <p>Danışmanlık talebiniz veya sorularınız için aşağıdaki formu doldurabilirsiniz. Tüm mesajlar gizlilik prensibiyle korunur.</p>
                    </div>

                    <form method="POST" action="<?= SITE_URL ?>/iletisim" class="contact-styled-form">
                        <div class="form-row-2">
                            <div class="input-block">
                                <label for="contactName">Adınız Soyadınız <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input type="text" id="contactName" name="name" required placeholder="Adınız Soyadınız">
                                </div>
                            </div>

                            <div class="input-block">
                                <label for="contactPhone">Telefon Numaranız</label>
                                <div class="input-with-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <input type="tel" id="contactPhone" name="phone" placeholder="05XX XXX XX XX">
                                </div>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="input-block">
                                <label for="contactEmail">E-posta Adresiniz <span class="req-star">*</span></label>
                                <div class="input-with-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <input type="email" id="contactEmail" name="email" required placeholder="adiniz@example.com">
                                </div>
                            </div>

                            <div class="input-block">
                                <label for="contactSubject">Danışma Konusu</label>
                                <div class="input-with-icon">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                    <input type="text" id="contactSubject" name="subject" placeholder="Örn: Evlilik / Çift Terapisi">
                                </div>
                            </div>
                        </div>

                        <div class="input-block">
                            <label for="contactMsg">Mesajınız <span class="req-star">*</span></label>
                            <div class="textarea-with-icon">
                                <textarea id="contactMsg" name="message" required placeholder="Yaşadığınız durum veya danışmak istediğiniz konu hakkında kısaca bilgi veriniz..."></textarea>
                            </div>
                        </div>

                        <div style="margin: 14px 0 20px;">
                            <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 0.85rem; color: var(--text-secondary); cursor: pointer;">
                                <input type="checkbox" name="kvkk_consent" required style="margin-top: 3px;">
                                <span><a href="<?= SITE_URL ?>/kvkk" target="_blank" style="color: var(--primary); text-decoration: underline; font-weight: 500;">KVKK Aydınlatma Metni</a>'ni okudum, kişisel verilerimin iletişim ve danışmanlık talebim kapsamında işlenmesini onaylıyorum. <span class="req-star">*</span></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit-message">
                            <span>Mesajı Gönder</span>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

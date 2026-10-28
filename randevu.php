<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Randevu Al — ' . get_setting('counselor_name', 'Elif Karaca');
$metaDescription = 'Online veya yüz yüze seans için randevunuzu kolayca oluşturun. Müsait saatleri anında görüntüleyin.';

$db = get_db();

// Aktif Hizmetler
$stmt = $db->query("SELECT id, title, category, summary, duration, price FROM services WHERE is_active = 1 ORDER BY display_order ASC");
$services = $stmt->fetchAll();

$selectedServiceId = (int)($_GET['service_id'] ?? 0);

include __DIR__ . '/includes/header.php';
?>

<div class="appointment-page">
    <div class="container appointment-container">
        
        <div class="section-header" style="margin-bottom: 36px;">
            <span class="eyebrow">Online & Yüz Yüze Seans</span>
            <h2>Randevu Oluşturun</h2>
            <p>Aşağıdaki adımları takip ederek dilediğiniz danışmanlık hizmetini ve size uygun saat dilimini seçebilirsiniz.</p>
        </div>

        <div class="appointment-card">
            <!-- Adım Göstergesi -->
            <nav class="step-nav" aria-label="Randevu Adımları">
                <div class="step-item active" data-step="1">
                    <span class="step-number">1</span>
                    <span>Hizmet Türü</span>
                </div>
                <div class="step-item" data-step="2">
                    <span class="step-number">2</span>
                    <span>Tarih & Saat</span>
                </div>
                <div class="step-item" data-step="3">
                    <span class="step-number">3</span>
                    <span>Bilgileriniz</span>
                </div>
            </nav>

            <div class="step-content">
                <!-- ADIM 1: Hizmet Seçimi -->
                <section class="step-pane active" id="step1">
                    <h3 class="pane-title">Danışmanlık / Eğitim Türünü Seçiniz</h3>
                    <p class="pane-subtitle">Almak istediğiniz seans veya eğitim programını belirleyiniz.</p>

                    <div class="services-picker">
                        <?php foreach ($services as $svc): 
                            $isSelected = ($selectedServiceId > 0 && $selectedServiceId === $svc['id']) || ($selectedServiceId === 0 && $svc === reset($services));
                        ?>
                        <div class="service-pick-card <?= $isSelected ? 'selected' : '' ?>" 
                             data-id="<?= $svc['id'] ?>" 
                             data-title="<?= htmlspecialchars($svc['title']) ?>">
                            <div class="service-pick-title"><?= htmlspecialchars($svc['title']) ?></div>
                            <div class="service-pick-desc"><?= htmlspecialchars($svc['summary']) ?></div>
                            <div class="service-pick-meta">
                                ⏱ <?= htmlspecialchars($svc['duration']) ?> <?= $svc['price'] ? ' • ' . htmlspecialchars($svc['price']) : '' ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="step-actions" style="justify-content: flex-end;">
                        <button type="button" class="btn btn-primary" id="toStep2Btn">
                            <span>Tarih ve Saat Seçimine Geç</span>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </section>

                <!-- ADIM 2: Tarih & Saat Seçimi -->
                <section class="step-pane" id="step2">
                    <h3 class="pane-title">Randevu Tarihi ve Saati</h3>
                    <p class="pane-subtitle">Takvimden bir gün belirleyin ve müsait olan saat dilimini seçin. Dolu saatler otomatik olarak pasiftir.</p>

                    <div class="datetime-layout">
                        <!-- Sol: Tarih Seçimi -->
                        <div class="date-selector-box">
                            <label for="appointmentDateInput">Randevu Tarihi:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="appointmentDateInput" class="date-input" value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>

                            <div class="quick-days">
                                <button type="button" class="quick-day-btn" data-offset="0">Bugün</button>
                                <button type="button" class="quick-day-btn active" data-offset="1">Yarın</button>
                                <button type="button" class="quick-day-btn" data-offset="2">+2 Gün</button>
                                <button type="button" class="quick-day-btn" data-offset="3">+3 Gün</button>
                            </div>

                            <div class="slot-legend">
                                <div class="legend-item">
                                    <span class="legend-dot available"></span>
                                    <span>Müsait</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot booked"></span>
                                    <span>Dolu / Pasif</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot selected"></span>
                                    <span>Seçilen</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sağ: Canlı Saat Dilimleri -->
                        <div class="slots-container">
                            <div class="slots-header">
                                <h4>Müsait Saat Dilimleri</h4>
                            </div>

                            <div id="dayBlockedNotice" class="dayBlockedNotice" style="display:none; text-align:center; padding:30px 10px; color:var(--danger);">
                                <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 10px; display:block;"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                <strong style="display:block; font-size:1.1rem; margin-bottom:6px;">Seçilen Tarih Randevuya Kapalıdır</strong>
                                <span class="blocked-reason" style="font-size:0.9rem; color:var(--text-secondary);"></span>
                            </div>

                            <div class="slots-grid" id="slotsGrid">
                                <!-- JavaScript ile dinamik dolacak -->
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" id="backToStep1Btn">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
                            <span>Geri</span>
                        </button>
                        <button type="button" class="btn btn-primary" id="toStep3Btn" disabled>
                            <span>Bilgileri Doldurmaya Geç</span>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </section>

                <!-- ADIM 3: Danışan Bilgileri -->
                <section class="step-pane" id="step3">
                    <h3 class="pane-title">İletişim ve Seans Bilgileri</h3>
                    <p class="pane-subtitle">Randevunuzu tamamlamak ve size WhatsApp üzerinden teyit iletmek için lütfen bilgilerinizi giriniz.</p>

                    <!-- Seçim Özeti -->
                    <div class="booking-summary-card">
                        <div class="summary-title">Seçilen Randevu Detayları</div>
                        <div class="summary-row">
                            <span class="summary-label">Hizmet:</span>
                            <span class="summary-val" id="sumService">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Tarih:</span>
                            <span class="summary-val" id="sumDate">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Saat Dilimi:</span>
                            <span class="summary-val" id="sumTime">-</span>
                        </div>
                    </div>

                    <form id="bookingForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="clientName">Adınız Soyadınız <span class="required">*</span></label>
                                <input type="text" id="clientName" name="client_name" class="form-control" placeholder="Örn: Ayşe Yılmaz" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="clientPhone">WhatsApp / Telefon Numaranız <span class="required">*</span></label>
                                <input type="tel" id="clientPhone" name="client_phone" class="form-control" placeholder="Örn: 0555 123 45 67" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="clientEmail">E-posta Adresiniz (Opsiyonel)</label>
                                <input type="email" id="clientEmail" name="client_email" class="form-control" placeholder="adiniz@example.com">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="sessionType">Seans Türü Tercihi</label>
                                <select id="sessionType" name="session_type" class="form-control">
                                    <option value="Online">Online Görüntülü Seans (Google Meet / Zoom)</option>
                                    <option value="Yüz Yüze">Yüz Yüze Seans (Ofis / Kadıköy)</option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label" for="clientNotes">Danışmak İstediğiniz Konu / Notlar (Opsiyonel)</label>
                                <textarea id="clientNotes" name="notes" class="form-control" placeholder="Kısaca danışmak istediğiniz durumdan bahsedebilirsiniz..."></textarea>
                            </div>
                        </div>

                        <div class="step-actions">
                            <button type="button" class="btn btn-secondary" id="backToStep2Btn">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
                                <span>Saat Değiştir</span>
                            </button>
                            <button type="submit" class="btn btn-primary" style="background:#25D366; border-color:#25D366;">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M16 2.5C8.544 2.5 2.5 8.544 2.5 16c0 2.653.766 5.129 2.091 7.218L3 29.5l6.471-1.558A13.435 13.435 0 0 0 16 29.5c7.456 0 13.5-6.044 13.5-13.5S23.456 2.5 16 2.5zm0 24.582c-2.348 0-4.542-.693-6.386-1.892l-.458-.3-4.242 1.021 1.054-4.103-.314-.477A11.026 11.026 0 0 1 4.918 16c0-6.11 4.972-11.082 11.082-11.082 6.11 0 11.082 4.972 11.082 11.082 0 6.11-4.972 11.082-11.082 11.082zm6.079-8.314c-.334-.167-1.975-.975-2.281-1.086-.306-.111-.528-.167-.751.167-.223.334-.863 1.086-1.057 1.309-.195.223-.39.251-.724.084-.334-.167-1.411-.52-2.688-1.659-.993-.886-1.664-1.98-1.859-2.314-.195-.334-.021-.515.146-.681.151-.15.334-.39.501-.585.167-.195.223-.334.334-.557.111-.223.056-.418-.028-.585-.084-.167-.751-1.81-1.03-2.478-.271-.651-.547-.563-.751-.573l-.641-.01c-.223 0-.585.084-.891.418-.306.334-1.17 1.142-1.17 2.784 0 1.643 1.198 3.23 1.365 3.453.167.223 2.356 3.6 5.709 5.049.798.345 1.42.551 1.905.705.801.254 1.53.218 2.107.132.643-.096 1.975-.808 2.253-1.588.278-.78.278-1.448.195-1.588-.084-.139-.306-.223-.64-.39z"/></svg>
                                <span>Randevuyu Onayla ve WhatsApp'a Gönder</span>
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>

    </div>
</div>

<!-- WhatsApp Onay & Yönlendirme Modalı -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon-success">
            <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <h3>Randevunuz Alındı!</h3>
        <p>Randevu kaydınız veritabanımıza işlendi ve seçtiğiniz saat sistemde rezerve edildi. Şimdi danışmanımıza WhatsApp üzerinden bildirim açılıyor.</p>

        <div class="modal-appointment-info">
            <div>👤 <strong>Danışan:</strong> <span id="modalClientName"></span></div>
            <div>🗓 <strong>Zaman:</strong> <span id="modalDateTime"></span></div>
            <div>🎯 <strong>Hizmet:</strong> <span id="modalService"></span></div>
        </div>

        <a href="#" id="modalWaBtn" target="_blank" class="btn btn-whatsapp-confirm btn-block">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M16 2.5C8.544 2.5 2.5 8.544 2.5 16c0 2.653.766 5.129 2.091 7.218L3 29.5l6.471-1.558A13.435 13.435 0 0 0 16 29.5c7.456 0 13.5-6.044 13.5-13.5S23.456 2.5 16 2.5zm0 24.582c-2.348 0-4.542-.693-6.386-1.892l-.458-.3-4.242 1.021 1.054-4.103-.314-.477A11.026 11.026 0 0 1 4.918 16c0-6.11 4.972-11.082 11.082-11.082 6.11 0 11.082 4.972 11.082 11.082 0 6.11-4.972 11.082-11.082 11.082zm6.079-8.314c-.334-.167-1.975-.975-2.281-1.086-.306-.111-.528-.167-.751.167-.223.334-.863 1.086-1.057 1.309-.195.223-.39.251-.724.084-.334-.167-1.411-.52-2.688-1.659-.993-.886-1.664-1.98-1.859-2.314-.195-.334-.021-.515.146-.681.151-.15.334-.39.501-.585.167-.195.223-.334.334-.557.111-.223.056-.418-.028-.585-.084-.167-.751-1.81-1.03-2.478-.271-.651-.547-.563-.751-.573l-.641-.01c-.223 0-.585.084-.891.418-.306.334-1.17 1.142-1.17 2.784 0 1.643 1.198 3.23 1.365 3.453.167.223 2.356 3.6 5.709 5.049.798.345 1.42.551 1.905.705.801.254 1.53.218 2.107.132.643-.096 1.975-.808 2.253-1.588.278-.78.278-1.448.195-1.588-.084-.139-.306-.223-.64-.39z"/></svg>
            <span>WhatsApp Bildirimini Aç</span>
        </a>

        <div style="margin-top: 16px;">
            <a href="<?= SITE_URL ?>/" class="btn-ghost" style="border:none; color:var(--text-muted); font-size:0.85rem;">Anasayfaya Dön</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

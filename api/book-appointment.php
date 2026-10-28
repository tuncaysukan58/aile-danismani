<?php
/**
 * Yeni Randevu Kayıt ve WhatsApp Bildirim API'si
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek türü']);
    exit;
}

$serviceId = (int)($_POST['service_id'] ?? 0);
$clientName = clean($_POST['client_name'] ?? '');
$clientPhone = clean($_POST['client_phone'] ?? '');
$clientEmail = clean($_POST['client_email'] ?? '');
$appointmentDate = clean($_POST['appointment_date'] ?? '');
$appointmentTime = clean($_POST['appointment_time'] ?? '');
$notes = clean($_POST['notes'] ?? '');

// Doğrulamalar
if (empty($clientName) || empty($clientPhone) || empty($appointmentDate) || empty($appointmentTime)) {
    echo json_encode(['success' => false, 'message' => 'Lütfen ad, telefon, tarih ve saat alanlarını eksiksiz doldurunuz.']);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointmentDate)) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz tarih formatı']);
    exit;
}

$today = date('Y-m-d');
if ($appointmentDate < $today) {
    echo json_encode(['success' => false, 'message' => 'Geçmiş tarihlere randevu oluşturulamaz.']);
    exit;
}

$db = get_db();

// Hizmet Adı
$serviceTitle = 'Genel Aile Danışmanlığı';
if ($serviceId > 0) {
    $stmt = $db->prepare("SELECT title FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);
    $svc = $stmt->fetch();
    if ($svc) {
        $serviceTitle = $svc['title'];
    }
}

// Çift Rezervasyon Kontrolü (Double-Booking Prevention)
// 1. Zaten bir randevu var mı?
$checkApp = $db->prepare("SELECT COUNT(*) as cnt FROM appointments WHERE appointment_date = ? AND appointment_time = ? AND status IN ('pending', 'confirmed')");
$checkApp->execute([$appointmentDate, $appointmentTime]);
if ($checkApp->fetch()['cnt'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Üzgünüz, seçtiğiniz saat dilimi az önce başka bir danışan tarafından rezerve edildi. Lütfen başka bir saat seçiniz.']);
    exit;
}

// 2. Bu gün veya saat bloklu mu?
$checkBlock = $db->prepare("SELECT COUNT(*) as cnt FROM blocked_slots WHERE block_date = ? AND (block_time = ? OR block_time IS NULL OR block_time = '')");
$checkBlock->execute([$appointmentDate, $appointmentTime]);
if ($checkBlock->fetch()['cnt'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Seçilen saat dilimi danışman tarafından randevuya kapatılmıştır.']);
    exit;
}

// Veritabanına Kaydet
try {
    $ins = $db->prepare("INSERT INTO appointments (service_id, client_name, client_phone, client_email, appointment_date, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $ins->execute([$serviceId ?: null, $clientName, $clientPhone, $clientEmail, $appointmentDate, $appointmentTime, $notes]);
    $appointmentId = $db->lastInsertId();

    // WhatsApp Bildirim Mesajı Oluştur
    $counselorPhone = get_setting('whatsapp_number', '905551234567');
    $counselorPhone = preg_replace('/[^0-9]/', '', $counselorPhone);
    if (substr($counselorPhone, 0, 1) === '0') {
        $counselorPhone = '90' . substr($counselorPhone, 1);
    } elseif (strlen($counselorPhone) === 10) {
        $counselorPhone = '90' . $counselorPhone;
    }

    $formattedDate = format_date_tr($appointmentDate);

    $waMessage = "🌿 *YENİ RANDEVU TALEBİ* 🌿\n\n";
    $waMessage .= "👤 *Danışan:* " . $clientName . "\n";
    $waMessage .= "📞 *Telefon:* " . $clientPhone . "\n";
    if ($clientEmail) {
        $waMessage .= "✉️ *E-posta:* " . $clientEmail . "\n";
    }
    $waMessage .= "🗓 *Tarih:* " . $formattedDate . "\n";
    $waMessage .= "⏰ *Saat:* " . $appointmentTime . "\n";
    $waMessage .= "🎯 *Hizmet / Konu:* " . $serviceTitle . "\n";
    if (!empty($notes)) {
        $waMessage .= "📝 *Danışan Notu:* " . $notes . "\n";
    }
    $waMessage .= "\n_Web sitesi randevu sistemi üzerinden otomatik oluşturulmuştur._";

    $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $counselorPhone . "&text=" . urlencode($waMessage);

    echo json_encode([
        'success' => true,
        'message' => 'Randevu talebiniz başarıyla oluşturuldu! Şimdi WhatsApp üzerinden teyit gönderiliyor...',
        'appointment_id' => $appointmentId,
        'whatsapp_url' => $whatsappUrl,
        'client_name' => $clientName,
        'date' => $formattedDate,
        'time' => $appointmentTime
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()]);
}

<?php
/**
 * Seçilen tarihe ait randevu saatlerini ve doluluk durumlarını döndüren API
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/config.php';

$date = clean($_GET['date'] ?? '');

if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz tarih formatı']);
    exit;
}

$today = date('Y-m-d');
if ($date < $today) {
    echo json_encode(['success' => false, 'message' => 'Geçmiş bir tarihe randevu alınamaz']);
    exit;
}

$db = get_db();

// 1. Günün tamamı bloklanmış mı?
$stmt = $db->prepare("SELECT reason FROM blocked_slots WHERE block_date = ? AND (block_time IS NULL OR block_time = '') LIMIT 1");
$stmt->execute([$date]);
$blockedDay = $stmt->fetch();

if ($blockedDay) {
    echo json_encode([
        'success' => true,
        'date' => $date,
        'is_day_blocked' => true,
        'reason' => $blockedDay['reason'] ?: 'Bu gün randevuya kapalıdır.',
        'slots' => []
    ]);
    exit;
}

// 2. Sistemde tanımlı standart saat dilimleri
$rawSlots = get_setting('available_slots', '10:00,11:15,13:00,14:15,15:30,16:45,18:00');
$allSlots = array_filter(array_map('trim', explode(',', $rawSlots)));

// 3. O güne ait dolu randevuları çek (onaylı veya beklemede)
$stmt = $db->prepare("SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status IN ('pending', 'confirmed')");
$stmt->execute([$date]);
$bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 4. O güne ait tekil bloklanmış saatleri çek
$stmt = $db->prepare("SELECT block_time FROM blocked_slots WHERE block_date = ? AND block_time IS NOT NULL AND block_time != ''");
$stmt->execute([$date]);
$blockedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

$slotData = [];
$currentDateTime = date('Y-m-d H:i');

foreach ($allSlots as $time) {
    $isBooked = in_array($time, $bookedTimes);
    $isBlocked = in_array($time, $blockedTimes);
    
    // Eğer bugün ise ve saat geçmişse de pasif yapalım
    $isPast = false;
    if ($date === $today) {
        $slotDateTime = $date . ' ' . $time;
        if ($slotDateTime < $currentDateTime) {
            $isPast = true;
        }
    }

    $available = !$isBooked && !$isBlocked && !$isPast;
    $statusText = 'Müsait';
    if ($isBooked) $statusText = 'Dolu';
    elseif ($isBlocked) $statusText = 'Kapalı';
    elseif ($isPast) $statusText = 'Geçmiş';

    $slotData[] = [
        'time' => $time,
        'available' => $available,
        'status' => $statusText
    ];
}

echo json_encode([
    'success' => true,
    'date' => $date,
    'is_day_blocked' => false,
    'slots' => $slotData
]);

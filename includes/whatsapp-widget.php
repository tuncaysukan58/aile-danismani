<?php
$counselorPhone = get_setting('whatsapp_number', '905551234567');
$cleanWa = preg_replace('/[^0-9]/', '', $counselorPhone);
if (substr($cleanWa, 0, 1) === '0') $cleanWa = '90' . substr($cleanWa, 1);
?>
<!-- WhatsApp Sabit Widget -->
<div class="whatsapp-float-container">
    <div class="whatsapp-bubble" id="whatsappBubble">
        <span class="bubble-title">Merhaba! 👋</span>
        <span class="bubble-text">Randevu veya danışmanlık hakkında WhatsApp'tan bilgi alabilirsiniz.</span>
        <button class="bubble-close" onclick="document.getElementById('whatsappBubble').style.display='none'">×</button>
    </div>
    <a href="https://api.whatsapp.com/send?phone=<?= $cleanWa ?>&text=<?= urlencode('Merhaba, aile danışmanlığı ve randevu hakkında bilgi almak istiyorum.') ?>" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="whatsapp-float-btn" 
       aria-label="WhatsApp ile İletişime Geçin">
        <div class="whatsapp-pulse"></div>
        <svg viewBox="0 0 32 32" width="30" height="30" fill="currentColor">
            <path d="M16 2.5C8.544 2.5 2.5 8.544 2.5 16c0 2.653.766 5.129 2.091 7.218L3 29.5l6.471-1.558A13.435 13.435 0 0 0 16 29.5c7.456 0 13.5-6.044 13.5-13.5S23.456 2.5 16 2.5zm0 24.582c-2.348 0-4.542-.693-6.386-1.892l-.458-.3-4.242 1.021 1.054-4.103-.314-.477A11.026 11.026 0 0 1 4.918 16c0-6.11 4.972-11.082 11.082-11.082 6.11 0 11.082 4.972 11.082 11.082 0 6.11-4.972 11.082-11.082 11.082zm6.079-8.314c-.334-.167-1.975-.975-2.281-1.086-.306-.111-.528-.167-.751.167-.223.334-.863 1.086-1.057 1.309-.195.223-.39.251-.724.084-.334-.167-1.411-.52-2.688-1.659-.993-.886-1.664-1.98-1.859-2.314-.195-.334-.021-.515.146-.681.151-.15.334-.39.501-.585.167-.195.223-.334.334-.557.111-.223.056-.418-.028-.585-.084-.167-.751-1.81-1.03-2.478-.271-.651-.547-.563-.751-.573l-.641-.01c-.223 0-.585.084-.891.418-.306.334-1.17 1.142-1.17 2.784 0 1.643 1.198 3.23 1.365 3.453.167.223 2.356 3.6 5.709 5.049.798.345 1.42.551 1.905.705.801.254 1.53.218 2.107.132.643-.096 1.975-.808 2.253-1.588.278-.78.278-1.448.195-1.588-.084-.139-.306-.223-.64-.39z"/>
        </svg>
    </a>
</div>

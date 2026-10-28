/**
 * Randevu Sihirbazı & Canlı Saat/Tarih & WhatsApp Entegrasyonu
 */

document.addEventListener('DOMContentLoaded', function () {
    const apiBase = window.SITE_URL ? `${window.SITE_URL}/api` : 'api';

    // Randevu Durum Nesnesi
    const booking = {
        serviceId: 0,
        serviceTitle: '',
        date: '',
        time: '',
        clientName: '',
        clientPhone: '',
        clientEmail: '',
        notes: ''
    };

    // DOM Elemanları
    const serviceCards = document.querySelectorAll('.service-pick-card');
    const dateInput = document.getElementById('appointmentDateInput');
    const quickDayBtns = document.querySelectorAll('.quick-day-btn');
    const slotsGrid = document.getElementById('slotsGrid');
    const dayBlockedNotice = document.getElementById('dayBlockedNotice');
    const bookingForm = document.getElementById('bookingForm');
    
    // Adım Geçişleri
    const stepItems = document.querySelectorAll('.step-item');
    const stepPanes = document.querySelectorAll('.step-pane');
    const toStep2Btn = document.getElementById('toStep2Btn');
    const toStep3Btn = document.getElementById('toStep3Btn');
    const backToStep1Btn = document.getElementById('backToStep1Btn');
    const backToStep2Btn = document.getElementById('backToStep2Btn');

    // Özet Alanları
    const sumService = document.getElementById('sumService');
    const sumDate = document.getElementById('sumDate');
    const sumTime = document.getElementById('sumTime');

    // Onay Modalı
    const confirmModal = document.getElementById('confirmModal');
    const modalClientName = document.getElementById('modalClientName');
    const modalDateTime = document.getElementById('modalDateTime');
    const modalService = document.getElementById('modalService');
    const modalWaBtn = document.getElementById('modalWaBtn');

    function goToStep(stepNumber) {
        stepItems.forEach((item, idx) => {
            if (idx + 1 === stepNumber) {
                item.classList.add('active');
                item.classList.remove('completed');
            } else if (idx + 1 < stepNumber) {
                item.classList.remove('active');
                item.classList.add('completed');
            } else {
                item.classList.remove('active', 'completed');
            }
        });

        stepPanes.forEach((pane, idx) => {
            if (idx + 1 === stepNumber) {
                pane.classList.add('active');
            } else {
                pane.classList.remove('active');
            }
        });

        window.scrollTo({ top: document.querySelector('.appointment-card').offsetTop - 100, behavior: 'smooth' });
    }

    // 1. Hizmet Seçimi
    serviceCards.forEach(card => {
        card.addEventListener('click', function () {
            serviceCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            booking.serviceId = this.dataset.id;
            booking.serviceTitle = this.dataset.title;
            if (toStep2Btn) toStep2Btn.disabled = false;
        });
    });

    // İlk hizmeti varsayılan seç
    if (serviceCards.length > 0) {
        serviceCards[0].click();
    }

    if (toStep2Btn) {
        toStep2Btn.addEventListener('click', function () {
            if (!booking.serviceId) {
                alert('Lütfen bir danışmanlık veya eğitim türü seçiniz.');
                return;
            }
            goToStep(2);
            // Tarih ve saatleri yükle
            if (!dateInput.value) {
                setQuickDate(1); // Yarın
            } else {
                loadSlots(dateInput.value);
            }
        });
    }

    if (backToStep1Btn) {
        backToStep1Btn.addEventListener('click', () => goToStep(1));
    }

    // 2. Tarih Seçimi
    function formatDateYMD(d) {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function setQuickDate(offsetDays) {
        const d = new Date();
        d.setDate(d.getDate() + offsetDays);
        const ymd = formatDateYMD(d);
        if (dateInput) {
            dateInput.value = ymd;
            dateInput.min = formatDateYMD(new Date());
            loadSlots(ymd);
        }
    }

    quickDayBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            quickDayBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const offset = parseInt(this.dataset.offset || 0, 10);
            setQuickDate(offset);
        });
    });

    if (dateInput) {
        // En erken bugün seçilebilir
        dateInput.min = formatDateYMD(new Date());
        dateInput.addEventListener('change', function () {
            quickDayBtns.forEach(b => b.classList.remove('active'));
            loadSlots(this.value);
        });
    }

    // 3. Saat Dilimlerini API'den Yükle (Dolu/Pasif Mantığı)
    async function loadSlots(dateStr) {
        if (!slotsGrid) return;

        booking.date = dateStr;
        booking.time = '';
        if (toStep3Btn) toStep3Btn.disabled = true;

        slotsGrid.innerHTML = `
            <div style="grid-column: span 3; text-align: center; padding: 30px; color: var(--text-muted);">
                <div class="spinner" style="display:inline-block; width:24px; height:24px; border:3px solid var(--border-color); border-top-color:var(--primary); border-radius:50%; animation:spin 0.8s linear infinite; margin-bottom:8px;"></div>
                <div>Müsait saatler kontrol ediliyor...</div>
            </div>
        `;
        if (dayBlockedNotice) dayBlockedNotice.style.display = 'none';

        try {
            const res = await fetch(`${apiBase}/get-slots.php?date=${encodeURIComponent(dateStr)}`);
            const data = await res.json();

            if (!data.success) {
                slotsGrid.innerHTML = `<div style="grid-column: span 3; text-align: center; color: var(--danger);">${data.message}</div>`;
                return;
            }

            if (data.is_day_blocked) {
                slotsGrid.innerHTML = '';
                if (dayBlockedNotice) {
                    dayBlockedNotice.style.display = 'block';
                    dayBlockedNotice.querySelector('.blocked-reason').textContent = data.reason || 'Bu gün danışman tarafından randevuya kapatılmıştır.';
                }
                return;
            }

            slotsGrid.innerHTML = '';
            if (!data.slots || data.slots.length === 0) {
                slotsGrid.innerHTML = '<div style="grid-column: span 3; text-align: center; color: var(--text-muted); padding: 20px;">Bu tarihte tanımlı randevu saati bulunmuyor.</div>';
                return;
            }

            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'slot-btn' + (slot.available ? '' : ' disabled');
                btn.dataset.time = slot.time;
                btn.disabled = !slot.available;

                btn.innerHTML = `
                    <span class="slot-time">${slot.time}</span>
                    <span class="slot-status">${slot.status}</span>
                `;

                if (slot.available) {
                    btn.addEventListener('click', function () {
                        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
                        this.classList.add('selected');
                        booking.time = slot.time;
                        if (toStep3Btn) toStep3Btn.disabled = false;
                    });
                }

                slotsGrid.appendChild(btn);
            });

        } catch (err) {
            slotsGrid.innerHTML = '<div style="grid-column: span 3; text-align: center; color: var(--danger); padding: 20px;">Saatler yüklenirken bir hata oluştu.</div>';
        }
    }

    if (toStep3Btn) {
        toStep3Btn.addEventListener('click', function () {
            if (!booking.date || !booking.time) {
                alert('Lütfen bir tarih ve uygun bir saat seçiniz.');
                return;
            }

            // Özet alanlarını doldur
            if (sumService) sumService.textContent = booking.serviceTitle;
            if (sumDate) sumDate.textContent = booking.date;
            if (sumTime) sumTime.textContent = booking.time;

            goToStep(3);
        });
    }

    if (backToStep2Btn) {
        backToStep2Btn.addEventListener('click', () => goToStep(2));
    }

    // 4. Form Gönderimi & WhatsApp Tetikleme
    if (bookingForm) {
        bookingForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = bookingForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Kaydediliyor...</span>';

            const formData = new FormData(bookingForm);
            formData.append('service_id', booking.serviceId);
            formData.append('appointment_date', booking.date);
            formData.append('appointment_time', booking.time);

            try {
                const res = await fetch(`${apiBase}/book-appointment.php`, {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();

                if (!result.success) {
                    alert(result.message || 'Randevu kaydedilemedi.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    return;
                }

                // Başarılı! Onay Modalı Göster
                if (confirmModal) {
                    if (modalClientName) modalClientName.textContent = result.client_name;
                    if (modalDateTime) modalDateTime.textContent = `${result.date} saat ${result.time}`;
                    if (modalService) modalService.textContent = booking.serviceTitle;
                    if (modalWaBtn) modalWaBtn.href = result.whatsapp_url;

                    confirmModal.classList.add('active');

                    // 1.5 saniye sonra otomatik WhatsApp sekmesi aç
                    setTimeout(() => {
                        window.open(result.whatsapp_url, '_blank');
                    }, 1200);
                } else {
                    // Modal yoksa doğrudan WhatsApp'a git
                    window.location.href = result.whatsapp_url;
                }

            } catch (err) {
                alert('Sunucuyla iletişim kurulurken bir hata oluştu.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
});

// CSS spinner keyframe
const styleEl = document.createElement('style');
styleEl.textContent = `@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }`;
document.head.appendChild(styleEl);

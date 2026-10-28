<?php
require_once __DIR__ . '/config/config.php';
$counselorName = get_setting('counselor_name', 'Elif Karaca');
$pageTitle = 'Hakkımda — ' . $counselorName;
$metaDescription = 'Uzm. Aile Danışmanı ' . $counselorName . ' akademik geçmişi, uzmanlık alanları, sertifikaları ve aile danışmanlığı felsefesi.';

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px;">
    <!-- Üst Başlık -->
    <div class="section-header" style="text-align: left; max-width: 100%; margin-bottom: 40px;">
        <span class="eyebrow">Özgeçmiş & Yaklaşım</span>
        <h1 style="font-size: 3rem; margin-bottom: 16px;"><?= htmlspecialchars($counselorName) ?></h1>
        <p style="font-size: 1.2rem; color: var(--primary); font-weight: 500;">
            <?= htmlspecialchars(get_setting('counselor_title', 'Uzm. Aile Danışmanı & Psikolojik Danışman')) ?>
        </p>
    </div>

    <!-- İki Kolonlu Biyo & Fotoğraf -->
    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 60px; align-items: flex-start; margin-bottom: 70px;">
        <div>
            <div style="font-size: 1.1rem; line-height: 1.85; color: var(--text-secondary); display: flex; flex-direction: column; gap: 20px;">
                <p>
                    <?= nl2br(htmlspecialchars(get_setting('about_text_full', 'İstanbul Üniversitesi Psikolojik Danışmanlık ve Rehberlik Bölümü mezuniyetinin ardından Klinik Psikoloji ve Aile Danışmanlığı alanında yüksek lisansını tamamlamıştır. Gottman Çift Terapisi, Şema Terapi ve Deneyimsel Oyun Terapisi eğitimlerini uluslararası akreditasyonla tamamlayarak danışanlarına kanıta dayalı ve şefkatli bir terapi alanı sunmaktadır.'))) ?>
                </p>
                <p>
                    Meslek hayatı boyunca binlerce aile, evli çift ve ebeveynle çalışan Karaca; danışmanlık sürecini sadece bir problem çözme seansı olarak değil, bireylerin kendi iç kaynaklarını keşfettikleri ve ilişkilerinde derin bir duygusal güven ortamı kurdukları bir yolculuk olarak ele almaktadır.
                </p>
            </div>

            <!-- Eğitim Geçmişi Zaman Çizelgesi -->
            <div style="margin-top: 48px;">
                <h3 style="font-size: 1.6rem; margin-bottom: 24px;">Akademik ve Mesleki Geçmiş</h3>
                
                <div style="display: flex; flex-direction: column; gap: 24px; border-left: 2px solid var(--border-color); padding-left: 24px; position: relative;">
                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">2012 — Günümüz</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">Özel Aile & Çift Danışmanlığı Uygulaması</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">Bireysel, çift ve grup danışmanlığı, ebeveyn okulu seminerleri ve kurumsal atölyeler.</p>
                    </div>

                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">2015 — 2017</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">Gottman Çift Terapisi 1. ve 2. Düzey Uzmanlığı</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">The Gottman Institute akrediteli çiftlerde çatışma yönetimi ve yakınlık inşası.</p>
                    </div>

                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">2010 — 2012</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">Yüksek Lisans: Aile Danışmanlığı & Klinik Psikoloji</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">Evlilik doyumunu etkileyen bağlanma stilleri üzerine tez çalışması.</p>
                    </div>

                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">2006 — 2010</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">İstanbul Üniversitesi — Psikolojik Danışmanlık ve Rehberlik</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">Lisans eğitimi (Onur Derecesi).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf: Büyük Portre & İletişim Kartı -->
        <div>
            <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); margin-bottom: 30px;">
                <img src="<?= SITE_URL ?>/assets/images/counselor.jpg" alt="<?= htmlspecialchars($counselorName) ?>" style="width: 100%;">
            </div>

            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-sm);">
                <h4 style="font-size: 1.2rem; margin-bottom: 16px;">Danışmanlık Seansları</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.6;">
                    Seanslarımız İstanbul Kadıköy ofisimizde yüz yüze veya Türkiye ve yurt dışındaki danışanlarımız için online görüntülü olarak gerçekleştirilmektedir.
                </p>
                <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary btn-block">Randevu Al</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

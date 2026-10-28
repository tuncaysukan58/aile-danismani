<?php
require_once __DIR__ . '/config/config.php';
$counselorName = get_setting('counselor_name', 'Çağla Tokyürek');
$pageTitle = 'Hakkımda — ' . $counselorName . ' | Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı';
$metaDescription = $counselorName . ' özgeçmişi, Atatürk Üniversitesi Çocuk Gelişimi lisansı, Arel Üniversitesi Aile Danışmanlığı eğitimi ve tamamladığı uzmanlık sertifikaları.';

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px;">
    <!-- Üst Başlık -->
    <div class="section-header" style="text-align: left; max-width: 100%; margin-bottom: 40px;">
        <span class="eyebrow">Özgeçmiş & Uzmanlık</span>
        <h1 style="font-size: 3rem; margin-bottom: 14px;"><?= htmlspecialchars($counselorName) ?></h1>
        <p style="font-size: 1.25rem; color: var(--primary); font-weight: 600;">
            <?= htmlspecialchars(get_setting('counselor_title', 'Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı')) ?>
        </p>
    </div>

    <!-- İki Kolonlu Biyo & Fotoğraf -->
    <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 60px; align-items: flex-start; margin-bottom: 70px;">
        <div>
            <div style="font-size: 1.1rem; line-height: 1.9; color: var(--text-secondary); display: flex; flex-direction: column; gap: 20px;">
                <p>
                    <strong>Atatürk Üniversitesi Çocuk Gelişimi Bölümü lisans mezunu</strong> olan <?= htmlspecialchars($counselorName) ?>, akademik çalışmalarını psikoloji alanında kararlılıkla sürdürmektedir. Lisans eğitiminin ardından <strong>İstanbul Arel Üniversitesi Aile Danışmanlığı</strong> uzmanlık programını başarıyla tamamlamıştır.
                </p>
                <p>
                    Mesleki odağının merkezinde <strong>Boşanma Danışmanlığı</strong> yer almaktadır. Boşanma aşamasındaki çiftlerle, boşanmış ya da boşanmaya karar veren ailelerle ve bu süreçten doğrudan etkilenen çocuklarıyla kapsamlı danışmanlık süreçleri yürütmektedir. Ayrılık sürecinin yetişkinler ve çocuklar açısından en az yıpratıcı biçimde yönetilmesi, sağlıklı ortak ebeveynlik dengesinin kurulması ve çocukların duygusal gelişiminin korunması için bilimsel ve şefkatli bir danışmanlık alanı sunmaktadır.
                </p>
                <p>
                    Aynı zamanda çocukların gelişim takibi, bilişsel ve duygusal değerlendirmeleri için uluslararası geçerli testler ve terapi teknikleri uygulayarak ailelere bütüncül bir rehberlik sağlamaktadır.
                </p>
            </div>

            <!-- Akademik Geçmiş -->
            <div style="margin-top: 50px;">
                <h3 style="font-size: 1.6rem; margin-bottom: 24px; color: var(--text-primary);">Akademik Eğitim</h3>
                
                <div style="display: flex; flex-direction: column; gap: 24px; border-left: 2px solid var(--border-color); padding-left: 24px; position: relative;">
                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">Akademik Çalışmalar</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">Psikoloji Alanında Akademik Çalışmalar</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">Gelişim psikolojisi, aile dinamikleri ve çocuk ruh sağlığı üzerine akademik araştırmalarını ve çalışmalarını sürdürmektedir.</p>
                    </div>

                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">Uzmanlık Eğitimi</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">İstanbul Arel Üniversitesi — Aile Danışmanlığı</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">Aile sistemleri, çift ilişkileri, evlilik dinamikleri ve çatışma çözümü üzerine kapsamlı uzmanlık eğitimi.</p>
                    </div>

                    <div style="position: relative;">
                        <span style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></span>
                        <div style="font-size: 0.85rem; color: var(--primary); font-weight: 600;">Lisans Eğitimi (Mezun)</div>
                        <h4 style="font-size: 1.15rem; margin: 4px 0;">Atatürk Üniversitesi — Çocuk Gelişimi Bölümü</h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary);">0-18 yaş çocukların bilişsel, duygusal, sosyal ve motor gelişimlerinin incelenmesi, gelişimsel tarama ve aile eğitimi lisans mezuniyeti.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ Taraf: Portre & İletişim Kartı -->
        <div>
            <div style="border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-lg); margin-bottom: 30px;">
                <img src="<?= SITE_URL ?>/assets/images/counselor.jpg" alt="<?= htmlspecialchars($counselorName) ?>" style="width: 100%; display: block; object-fit: cover;">
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-sm);">
                <h4 style="font-size: 1.2rem; margin-bottom: 12px;"><?= htmlspecialchars($counselorName) ?></h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px; line-height: 1.6;">
                    Yüz yüze seanslarımız ve tüm Türkiye ile yurt dışındaki danışanlarımız için online görüntülü seanslarımız mevcuttur.
                </p>
                <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px; font-size:0.9rem;">
                    <div>💬 <strong>WhatsApp:</strong> <a href="<?= get_whatsapp_url() ?>" target="_blank" style="color:var(--primary); font-weight:600;"><?= htmlspecialchars(get_whatsapp_display()) ?></a></div>
                    <div>📧 <strong>E-posta:</strong> <a href="mailto:<?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?>" style="color:var(--primary);"><?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?></a></div>
                </div>
                <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary btn-block">Randevu Oluştur</a>
            </div>
        </div>
    </div>

    <!-- Tamamladığı Eğitimler & Sertifikalar Grid (El Yazısı Notundaki 8 Madde) -->
    <div style="margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 60px;">
        <div style="text-align: center; margin-bottom: 40px;">
            <span class="eyebrow">Yetkinlikler & Sertifikalar</span>
            <h2 style="font-size: 2.2rem; margin-top: 8px;">Tamamladığı Uzmanlık Eğitimleri</h2>
            <p style="color: var(--text-secondary); max-width: 650px; margin: 10px auto 0;">
                Danışmanlık ve çocuk gelişimi süreçlerinde uyguladığı bilimsel yöntemler ve tamamladığı sertifikalı eğitim programları.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; transition: transform 0.2s ease;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">⚖️</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">1. Boşanma Danışmanlığı</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Boşanma sürecindeki çiftlerin iletişimi, velayet, ortak ebeveynlik planı ve ayrılık sürecinin çocuklara sağlıklı aktarımı.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">🌱</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">2. Yetişkin, Ergen ve Çocuk Psikolojisi</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Her yaş grubunun bilişsel, duygusal ve sosyal evrelerine özgü psikolojik dinamikler ve iletişim yaklaşımları.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">📊</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">3. Çocuk Değerlendirme Testleri</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Çocukların zihinsel, duygusal ve davranışsal gelişim düzeylerini ölçümleyen standart bilimsel test bataryaları.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">🧸</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">4. Oyun Terapisi</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Çocukların iç dünyalarını oyun yoluyla ifade etmelerini, duygusal tıkanıklık ve kaygılarını aşmalarını sağlayan terapi yöntemi.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">🎨</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">5. Çocuk Resimlerinin Psiko-Pedagojik Analizi</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Çocukların çizimlerindeki renk, çizgi, figür ve kompozisyonlar üzerinden bilinçdışı duygu, kaygı ve aile algısının analizi.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">🎯</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">6. MOXO Dikkat Testi</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Dikkat eksikliği, zamanlama problemi, dürtüsellik ve hiperaktiviteyi objektif olarak ölçen uluslararası geçerli bilgisayarlı test.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">📈</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">7. Denver II Gelişimsel Tarama Testi</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">0-6 yaş aralığındaki çocukların kişisel-sosyal, ince motor, dil ve kaba motor gelişimlerini tarayan standart klinik değerlendirme.</p>
            </div>

            <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
                <div style="font-size: 1.8rem; margin-bottom: 12px;">🎓</div>
                <h4 style="font-size: 1.15rem; margin-bottom: 8px;">8. Eğitim Danışmanlığı</h4>
                <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">Öğrencilerin okul başarısı, verimli ders çalışma alışkanlıkları, sınav motivasyonu ve ebeveyn-okul iş birliği rehberliği.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

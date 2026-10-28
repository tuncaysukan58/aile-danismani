<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'KVKK Aydınlatma Metni — ' . get_setting('counselor_name', 'Çağla Tokyürek');
$metaDescription = '6698 sayılı Kişisel Verilerin Korunması Kanunu uyarınca danışan kişisel verilerinin işlenmesi, korunması ve haklarınıza dair aydınlatma metni.';

include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding: 60px 24px 100px; max-width: 900px;">
    <!-- Üst Başlık -->
    <header style="margin-bottom: 36px; border-bottom: 1px solid var(--border-color); padding-bottom: 24px;">
        <span class="eyebrow">Yasal Bilgilendirme</span>
        <h1 style="font-size: 2.5rem; margin: 12px 0 10px; line-height: 1.3;">Kişisel Verilerin Korunması ve İşlenmesi Aydınlatma Metni</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            Son Güncelleme: <?= date('d.m.Y') ?> | 6698 Sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") Kapsamında
        </p>
    </header>

    <div class="legal-content" style="font-size: 1.05rem; line-height: 1.85; color: var(--text-secondary); display: flex; flex-direction: column; gap: 28px;">
        
        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">1. Veri Sorumlusunun Kimliği</h2>
            <p>
                6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, veri sorumlusu sıfatıyla 
                <strong><?= htmlspecialchars(get_setting('counselor_name', 'Çağla Tokyürek')) ?></strong> 
                (<em><?= htmlspecialchars(get_setting('counselor_title', 'Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı')) ?></em>) 
                tarafından, danışanlarımızın ve web sitemizi ziyaret eden kullanıcılarımızın kişisel verileri aşağıda açıklanan çerçevede hukuka, 
                dürüstlük kurallarına ve mesleki gizlilik ilkelerine uygun olarak işlenmekte ve korunmaktadır.
            </p>
        </section>

        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">2. İşlenen Kişisel Verileriniz</h2>
            <p>Web sitemiz üzerinden randevu oluştururken, iletişim formunu doldururken veya doğrudan bizimle iletişime geçtiğinizde işlenen verileriniz şunlardır:</p>
            <ul style="padding-left: 24px; margin-top: 8px; list-style-type: disc; display: flex; flex-direction: column; gap: 8px;">
                <li><strong>Kimlik Bilgileri:</strong> Adınız, soyadınız.</li>
                <li><strong>İletişim Bilgileri:</strong> Telefon numaranız, e-posta adresiniz, WhatsApp iletişim bilgileriniz.</li>
                <li><strong>Randevu & Süreç Bilgileri:</strong> Talep ettiğiniz danışmanlık hizmeti, randevu tarih ve saati, seans türü (Online / Yüz Yüze).</li>
                <li><strong>Danışan Notları:</strong> Danışmanlık talebinize ilişkin kendi rızanızla ilettiğiniz ön bilgiler ve mesaj içerikleri.</li>
                <li><strong>İşlem Güvenliği Bilgileri:</strong> Web sitesi kullanımınıza ilişkin temel log kayıtları ve teknik veriler.</li>
            </ul>
        </section>

        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">3. Kişisel Verilerin İşlenme Amaçları</h2>
            <p>Toplanan kişisel verileriniz aşağıdaki amaçlarla sınırlı ve ölçülü olarak işlenmektedir:</p>
            <ul style="padding-left: 24px; margin-top: 8px; list-style-type: disc; display: flex; flex-direction: column; gap: 8px;">
                <li>Danışmanlık ve çocuk gelişimi değerlendirme randevularınızın planlanması, teyit edilmesi ve takvim oluşturulması,</li>
                <li>Danışan ile iletişim kurulması, seans öncesi ve sonrası gerekli bilgilendirmelerin yapılması,</li>
                <li>İletişim formu ve WhatsApp üzerinden ilettiğiniz soru, görüş ve taleplerin yanıtlanması,</li>
                <li>Etik ve mesleki danışmanlık standartlarının gerektirdiği gizlilik ilkelerinin sağlanması,</li>
                <li>Yasal düzenlemelerden doğan hukuki yükümlülüklerin yerine getirilmesi.</li>
            </ul>
        </section>

        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">4. Kişisel Verilerin Aktarılması</h2>
            <p>
                Kişisel verileriniz ve danışmanlık süreci kapsamındaki bilgileriniz, <strong>mesleki sır ve danışan gizliliği ilkeleri gereğince kesinlikle üçüncü şahıslara, ticari şirketlere veya reklam/pazarlama amacıyla hiçbir kuruma aktarılmaz</strong>.
                Yalnızca yasal zorunluluk gerektiren durumlarda, yetkili adli veya idari mercilerin mevzuata uygun resmi talepleri ile sınırlı kalmak kaydıyla yetkili makamlarla paylaşılabilir.
            </p>
        </section>

        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">5. Kişisel Veri Toplamanın Yöntemi ve Hukuki Sebebi</h2>
            <p>
                Kişisel verileriniz; web sitemizdeki randevu ve iletişim formları, WhatsApp iletişim hattı ve e-posta vasıtasıyla elektronik ortamda doğrudan siz danışanlarımız tarafından iletilmesi suretiyle toplanır.
                Bu veriler, KVKK'nın 5. maddesinde belirtilen <em>"bir sözleşmenin kurulması veya ifasıyla doğrudan doğruya ilgili olması"</em>, <em>"veri sorumlusunun meşru menfaati"</em> ve <em>"ilgili kişinin açık rızası"</em> hukuki sebeplerine dayalı olarak işlenmektedir.
            </p>
        </section>

        <section>
            <h2 style="font-size: 1.4rem; color: var(--text-primary); margin-bottom: 12px;">6. İlgili Kişi Olarak Haklarınız (KVKK Madde 11)</h2>
            <p>KVKK'nın 11. maddesi uyarınca veri sahibi olarak aşağıdaki haklara sahipsiniz:</p>
            <ul style="padding-left: 24px; margin-top: 8px; list-style-type: disc; display: flex; flex-direction: column; gap: 8px;">
                <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme,</li>
                <li>İşlenmişse buna ilişkin bilgi talep etme,</li>
                <li>İşlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme,</li>
                <li>Eksik veya yanlış işlenmişse düzeltilmesini isteme,</li>
                <li>KVKK şartları çerçevesinde silinmesini veya yok edilmesini talep etme,</li>
                <li>İşlenen verilerin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme.</li>
            </ul>
        </section>

        <section style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <h3 style="font-size: 1.2rem; color: var(--text-primary); margin-bottom: 10px;">İletişim ve Başvuru</h3>
            <p style="margin-bottom: 12px;">
                KVKK kapsamındaki haklarınızı kullanmak ve kişisel verilerinizle ilgili sorularınızı iletmek için aşağıdaki kanallardan veri sorumlusuna ulaşabilirsiniz:
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.95rem;">
                <div>📧 <strong>E-posta:</strong> <a href="mailto:<?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?>" style="color: var(--primary); text-decoration: underline;"><?= htmlspecialchars(get_setting('email', 'curlycagla@gmail.com')) ?></a></div>
                <div>💬 <strong>WhatsApp:</strong> <a href="<?= get_whatsapp_url() ?>" target="_blank" style="color: var(--primary); text-decoration: underline;"><?= htmlspecialchars(get_whatsapp_display()) ?></a></div>
            </div>
        </section>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>


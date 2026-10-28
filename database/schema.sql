-- Aile Danışmanı Veritabanı Şeması (cPanel & MySQL Tüm Sürümleriyle %100 Uyumlu)
-- Karakter seti: utf8mb4 (Index limiti hatasını önlemek için UNIQUE slug alanları 190 karakterdir)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ayarlar Tablosu
CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(64) NOT NULL PRIMARY KEY,
    `value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Yöneticiler Tablosu
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Eğitimler & Hizmetler Tablosu (slug: VARCHAR(190) UNIQUE)
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    category VARCHAR(100) DEFAULT 'Danışmanlık',
    summary TEXT,
    content TEXT,
    duration VARCHAR(50) DEFAULT '50 Dakika',
    price VARCHAR(50) NULL,
    image VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Randevular Tablosu
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NULL,
    client_name VARCHAR(120) NOT NULL,
    client_phone VARCHAR(30) NOT NULL,
    client_email VARCHAR(120) NULL,
    appointment_date DATE NOT NULL,
    appointment_time VARCHAR(20) NOT NULL,
    notes TEXT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (appointment_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Kapalı Günler / Saatler (Blokaj)
CREATE TABLE IF NOT EXISTS blocked_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    block_date DATE NOT NULL,
    block_time VARCHAR(20) NULL,
    reason VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_bdate (block_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Blog Makaleleri Tablosu (slug: VARCHAR(190) UNIQUE)
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    category VARCHAR(100) DEFAULT 'Aile & İletişim',
    summary TEXT,
    content MEDIUMTEXT,
    cover_image VARCHAR(255) NULL,
    reading_time VARCHAR(20) DEFAULT '4 dk',
    views INT DEFAULT 0,
    status VARCHAR(20) DEFAULT 'published',
    published_at DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Gelen Mesajlar Tablosu
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(200) NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================================
-- BAŞLANGIÇ VERİLERİ (1 TIKLA TÜM SİTE VE ÇAĞLA TOKYÜREK AYARLARI YÜKLENİR)
-- =========================================================================

-- Varsayılan Admin (Kullanıcı: admin / Şifre: admin123)
INSERT INTO admins (id, username, password, name, email) 
VALUES (1, 'admin', '$2y$10$wN1G26F7Z13sU2nC/g1kP.R12JsqoI1bXv3YwY2XU9aPZ4xNqM1/6', 'Çağla TOKYÜREK', 'admin@example.com')
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- Site Ayarları
INSERT INTO settings (`key`, `value`) VALUES
('site_title', 'Çağla Tokyürek — Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı'),
('counselor_name', 'Çağla Tokyürek'),
('counselor_title', 'Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı'),
('eyebrow', 'Çocuk Gelişimi | Aile, Çift ve Boşanma Danışmanlığı'),
('hero_mission', 'Boşanma sürecinde çift ve çocuk odaklı rehberlik, aile içi ilişki dinamikleri ve çocuk gelişimi alanında bilimsel, şefkatli ve profesyonel danışmanlık.'),
('whatsapp_number', '@caglatokyurek'),
('phone', '+90 (555) 123 45 67'),
('email', 'curlycagla@gmail.com'),
('address', 'Kadıköy, İstanbul'),
('working_hours', 'Pazartesi - Cumartesi: 10:00 - 18:30 (Pazar Kapalı)'),
('available_slots', '10:00,11:15,13:00,14:15,15:30,16:45,18:00'),
('instagram', 'https://instagram.com/caglatokyurek'),
('youtube', 'https://youtube.com/@caglatokyurek'),
('linkedin', 'https://linkedin.com'),
('stat_experience', '8+ Yıl'),
('stat_clients', '1500+ Seans'),
('stat_seminars', '30+ Eğitim'),
('stat_followers', '50K+'),
('about_text_short', 'Atatürk Üniversitesi Çocuk Gelişimi lisans mezunu olan ve akademik çalışmalarını psikoloji alanında sürdüren Çağla Tokyürek; İstanbul Arel Üniversitesi Aile Danışmanlığı uzmanlığıyla boşanma sürecindeki çiftler, boşanmış aileler ve çocuklarla profesyonel danışmanlık süreçleri yürütmektedir.'),
('about_text_full', 'Atatürk Üniversitesi Çocuk Gelişimi Bölümü lisans mezunu olan Çağla Tokyürek, akademik çalışmalarını psikoloji alanında sürdürmektedir. İstanbul Arel Üniversitesi\'nde Aile Danışmanlığı eğitimini tamamlamıştır.\n\nÖzellikle boşanma alanında yoğunlaşarak; boşanma aşamasındaki çiftler, boşanmış ya da boşanmaya karar veren ailelerle ve bu süreçteki çocuklarıyla profesyonel çalışmalar yürütmektedir. Sürecin sağlıklı yürütülmesi, ebeveynlik iş birliğinin korunması ve çocukların gelişimsel ihtiyaçlarının gözetilmesi temel önceliğidir.\n\nTamamladığı uzmanlık eğitimleri arasında Boşanma Danışmanlığı, Yetişkin, Ergen ve Çocuk Psikolojisi Eğitimleri, Çocuk Değerlendirme Testleri, Oyun Terapisi, Çocuk Resimlerinin Psiko-Pedagojik Analizi (Resim Analizi), MOXO Dikkat Testi, Denver II Gelişimsel Tarama Testi ve Eğitim Danışmanlığı yer almaktadır.')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Örnek Eğitimler & Hizmetler
INSERT INTO services (id, title, slug, category, summary, content, duration, price, image, is_active, display_order) VALUES
(1, 'Boşanma Danışmanlığı & Çocuk Rehberliği', 'bosanma-danismanligi', 'Boşanma Danışmanlığı', 'Boşanma aşamasındaki çiftler, boşanmış aileler ve bu süreçteki çocukların psikolojik dengesini koruyan uzman rehberlik.', 'Boşanma sürecine karar verme, ayrılık sürecini çocuklara açıklama, velayet ve ortak ebeveynlik planı oluşturma, boşanma sonrası yeni yaşam düzenine uyum süreçlerinde bilimsel ve şefkatli danışmanlık sunulur.', '50 Dakika', 'Yüz Yüze / Online', 'assets/images/counselor.jpg', 1, 1),
(2, 'Aile ve Çift Danışmanlığı', 'aile-ve-cift-danismanligi', 'Aile & Çift', 'Eşler arası iletişim çatışmalarını çözme, ilişki bağlarını güçlendirme ve aile içi krizleri yönetme desteği.', 'İlişkilerde tekrar eden kısırdöngüler, iletişim engelleri, duygusal kopukluklar ve aile içi uyum problemleri ele alınır. Çiftlerin birbirini yargılamadan dinleyebileceği güvenli bir danışmanlık alanı sağlanır.', '50 Dakika', 'Yüz Yüze / Online', 'assets/images/therapy.jpg', 1, 2),
(3, 'Çocuk Değerlendirme & Gelişim Testleri', 'cocuk-degerlendirme-testleri', 'Çocuk Gelişimi', 'Denver II Gelişimsel Tarama Testi ve Çocuk Değerlendirme Testleri ile bilişsel, motor ve sosyal gelişim takibi.', '0-6 yaş ve okul dönemi çocukların gelişim evreleri standart bilimsel test bataryaları ile taranır. Olası gelişimsel gerilikler veya güçlü yönler tespit edilerek aileye bireysel gelişim haritası çıkarılır.', '50 Dakika', 'Yüz Yüze Seans', 'assets/images/seminar.jpg', 1, 3),
(4, 'MOXO Dikkat Testi Uygulaması', 'moxo-dikkat-testi', 'Test & Değerlendirme', 'Çocuk ve ergenlerde dikkat, zamanlama, dürtüsellik ve hiperaktivite düzeyini ölçen uluslararası geçerli test.', 'Görsel ve işitsel çeldiriciler eşliğinde bilgisayar ortamında uygulanan MOXO Dikkat Testi, çocuğun dikkat performansını ve çevresel uyaranlara tepkisini objektif olarak raporlar.', '45 Dakika', 'Yüz Yüze Seans', 'assets/images/seminar.jpg', 1, 4),
(5, 'Oyun Terapisi & Çocuk Resimleri Analizi', 'oyun-terapisi-ve-resim-analizi', 'Çocuk Terapisi', 'Oyun terapisi ve çocuk resimlerinin psiko-pedagojik analizi ile çocuğun iç dünyasını anlama ve duygusal iyileşme.', 'Çocuklar duygularını yetişkinler gibi sözcüklerle değil, oyun ve çizim yoluyla ifade eder. Oyun terapisi ve resim analiziyle kaygı, korku, öfke ve travma süreçleri güvenli bir ortamda çözümlenir.', '50 Dakika', 'Yüz Yüze Seans', 'assets/images/counselor.jpg', 1, 5),
(6, 'Eğitim Danışmanlığı & Ebeveyn Rehberliği', 'egitim-danismanligi-ve-ebeveyn-rehberligi', 'Eğitim Danışmanlığı', 'Okul uyumu, çalışma alışkanlıkları, ergenlik dönemi iletişimi ve bilinçli ebeveynlik desteği.', 'Okul başarısı, sınav dönemi kaygıları, ergenlikte ebeveyn-çocuk iletişimi ve sınır koyma dinamikleri üzerine ailelere özel yapılandırılmış rehberlik.', '50 Dakika', 'Yüz Yüze / Online', 'assets/images/therapy.jpg', 1, 6)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Örnek Blog Yazıları
INSERT INTO blog_posts (id, title, slug, category, summary, content, cover_image, reading_time, views, status, published_at) VALUES
(1, 'Çiftler Arasında Sağlıklı İletişim Kurmanın 5 Altın Kuralı', 'ciftler-arasinda-saglikli-iletisim-kurmanin-5-altin-kurali', 'İlişki & Çift', 'Tartışmaların kavgaya dönüşmesini engelleyen, birbirinizi gerçekten duymanızı sağlayan pratik iletişim prensipleri.', '<p>İlişkilerde çatışma kaçınılmazdır; ancak çatışmanın yıkıcı mı yoksa bağları güçlendirici mi olacağını belirleyen şey üslubumuzdur.</p><h3>1. "Sen" Dili Yerine "Ben" Dili Kullanın</h3><p>"Sen beni hiç dinlemiyorsun" yerine "Konuşurken dinlenmediğimi hissettiğimde üzülüyorum" demek savunmayı kırar.</p>', 'assets/images/therapy.jpg', '5 dk', 120, 'published', '2026-10-28'),
(2, 'Öfke Krizleri Yaşayan Çocuğa Nasıl Yaklaşmalıyız?', 'ofke-krizleri-yasayan-cocuga-nasil-yaklasmaliyiz', 'Çocuk & Ebeveyn', 'Tantrum anlarında sakin kalabilmenin, çocuğun duygusunu onaylayıp sınır koyabilmenin nörobiyolojik temelleri.', '<p>Çocuklarda öfke krizleri ebeveyne karşı yapılmış bir kasıt değil, henüz olgunlaşmamış prefrontal korteksin taşmasıdır.</p>', 'assets/images/counselor.jpg', '4 dk', 95, 'published', '2026-10-25'),
(3, 'Evlilikte Duygusal Güven ve Bağlanma Stilleri', 'evlilikte-duygusal-guven-ve-baglanma-stilleri', 'Evlilik Terapisi', 'Kaygılı ve kaçıngan bağlanma döngüsünü fark etmek ve ilişkide güvenli bir liman inşa etmek.', '<p>Çocuklukta kurduğumuz ilişki modelleri yetişkinlikte partnerimize verdiğimiz tepkileri belirler.</p>', 'assets/images/seminar.jpg', '6 dk', 88, 'published', '2026-10-20')
ON DUPLICATE KEY UPDATE title = VALUES(title);

SET FOREIGN_KEY_CHECKS = 1;

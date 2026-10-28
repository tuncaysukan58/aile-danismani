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
('site_title', 'Uzm. Aile Danışmanı Çağla TOKYÜREK | Aile, Çift & Ebeveyn Danışmanlığı'),
('counselor_name', 'Çağla TOKYÜREK'),
('counselor_title', 'Uzm. Aile Danışmanı & Psikolojik Danışman'),
('eyebrow', 'Aile & Çift Danışmanı, Eğitmen ve Konuşmacı'),
('hero_mission', 'Aile içi dinamikleri güçlendiren, ebeveynlik yolculuğunuza rehberlik eden ve ilişkilerdeki tıkanıklıkları sevgi ve bilimsel temellerle çözen bütüncül bir yaklaşım.'),
('whatsapp_number', '905551234567'),
('phone', '+90 (555) 123 45 67'),
('email', 'iletisim@caglatokyurek.com'),
('address', 'Bağdat Caddesi No: 142/5 Kadıköy, İstanbul'),
('working_hours', 'Pazartesi - Cumartesi: 10:00 - 18:30 (Pazar Kapalı)'),
('available_slots', '10:00,11:15,13:00,14:15,15:30,16:45,18:00'),
('instagram', 'https://instagram.com/caglatokyurek.aile'),
('youtube', 'https://youtube.com/@caglatokyurek'),
('linkedin', 'https://linkedin.com'),
('stat_experience', '12+ Yıl'),
('stat_clients', '2500+ Seans'),
('stat_seminars', '40+ Seminer'),
('stat_followers', '250K+'),
('about_text_short', 'İstanbul Üniversitesi Psikolojik Danışmanlık ve Rehberlik lisansının ardından Aile Danışmanlığı alanında uzmanlığını tamamlayan Çağla TOKYÜREK, 12 yılı aşkın süredir çiftler, ebeveynler ve çocuklarla çalışmaktadır.'),
('about_text_full', 'İstanbul Üniversitesi Psikolojik Danışmanlık ve Rehberlik Bölümü mezuniyetinin ardından Klinik Psikoloji ve Aile Danışmanlığı alanında yüksek lisansını tamamlamıştır. Gottman Çift Terapisi, Şema Terapi ve Deneyimsel Oyun Terapisi eğitimlerini uluslararası akreditasyonla tamamlayarak danışanlarına kanıta dayalı ve şefkatli bir terapi alanı sunmaktadır.\n\nTürkiye genelinde yüz yüze ve online danışmanlık hizmeti sunmakta; kurucusu olduğu "Bilinçli Ebeveynlik", "Evlilikte Biz Olabilmek" ve "Ergenlikte İletişim Köprüleri" seminer dizileriyle binlerce anne-babaya ulaşmaktadır.')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Örnek Eğitimler & Hizmetler
INSERT INTO services (id, title, slug, category, summary, content, duration, price, image, is_active, display_order) VALUES
(1, 'Bireysel Aile Danışmanlığı', 'bireysel-aile-danismanligi', 'Danışmanlık', 'Aile bireyleri arasındaki iletişim engellerini aşmak ve aile içi dengeleri yeniden kurmak için profesyonel destek.', 'Aile içindeki roller, kuşaklararası aktarımlar, kriz anları ve iletişim kopuklukları üzerine odaklanılan seanslarda; her bireyin kendini güvende ve anlaşılmış hissedeceği bir alan oluşturulur.', '50 Dakika', 'Yüz Yüze / Online', 'assets/images/counselor.jpg', 1, 1),
(2, 'Çift ve Evlilik Terapisi', 'cift-ve-evlilik-terapisi', 'Danışmanlık', 'Gottman yöntemi temelli, çiftlerin duygusal bağlarını onarmalarına ve çatışmaları yapıcı yönetmelerine rehberlik.', 'İlişkilerde tekrar eden kısırdöngüler, kırgınlıklar, güven sorunları ve ortak yaşam hedefleri yapılandırılmış seanslarla ele alınır. Birlikte sevgi ve saygıyla büyüyen bir ilişki inşa edilir.', '60 Dakika', 'Yüz Yüze / Online', 'assets/images/therapy.jpg', 1, 2),
(3, 'Bilinçli Ebeveynlik Atölyesi', 'bilincli-ebeveynlik-atolyesi', 'Eğitim & Seminer', 'Çocukların gelişim evrelerini anlama, kriz anlarında şefkatli sınırlar koyma ve pozitif disiplin eğitimi.', '4 haftalık modüler eğitim programı. Çocuklarda öfke nöbetleri, kardeş kıskançlığı, ekran bağımlılığı ve özgüven gelişimi konularında pratik uygulamalı atölye çalışması.', '4 Hafta (Haftada 2 Saat)', 'Grup Eğitimi', 'assets/images/seminar.jpg', 1, 3),
(4, 'Ergenlikte İletişim Köprüleri Semineri', 'ergenlikte-iletisim-kopruleri', 'Eğitim & Seminer', 'Ergenlik çağındaki gençlerin psikolojik dünyasını anlama ve çatışmasız sağlıklı iletişim kurma semineri.', 'Gençlerin kimlik arayışı, okul ve sınav kaygısı, aileyle çatışmalar ve dijital çağda ergenlik konuları interaktif vaka örnekleriyle ele alınır.', 'Tek Oturum (2.5 Saat)', 'Canlı / Online', 'assets/images/seminar.jpg', 1, 4),
(5, 'Boşanma Süreci ve Çocuk Rehberliği', 'bosanma-sureci-ve-cocuk-rehberligi', 'Danışmanlık', 'Ayrılık ve boşanma evresinde çocukların ruh sağlığını koruma, ortak ebeveynlik planı oluşturma desteği.', 'Eşler arasındaki bağ biterken ebeveynlik bağının sağlıklı sürdürülmesi ve çocukların bu süreci en az hasarla atlatması için özel yapılandırılmış danışmanlık.', '50 Dakika', 'Yüz Yüze / Online', 'assets/images/counselor.jpg', 1, 5)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Örnek Blog Yazıları
INSERT INTO blog_posts (id, title, slug, category, summary, content, cover_image, reading_time, views, status, published_at) VALUES
(1, 'Çiftler Arasında Sağlıklı İletişim Kurmanın 5 Altın Kuralı', 'ciftler-arasinda-saglikli-iletisim-kurmanin-5-altin-kurali', 'İlişki & Çift', 'Tartışmaların kavgaya dönüşmesini engelleyen, birbirinizi gerçekten duymanızı sağlayan pratik iletişim prensipleri.', '<p>İlişkilerde çatışma kaçınılmazdır; ancak çatışmanın yıkıcı mı yoksa bağları güçlendirici mi olacağını belirleyen şey üslubumuzdur.</p><h3>1. "Sen" Dili Yerine "Ben" Dili Kullanın</h3><p>"Sen beni hiç dinlemiyorsun" yerine "Konuşurken dinlenmediğimi hissettiğimde üzülüyorum" demek savunmayı kırar.</p>', 'assets/images/therapy.jpg', '5 dk', 120, 'published', '2026-10-28'),
(2, 'Öfke Krizleri Yaşayan Çocuğa Nasıl Yaklaşmalıyız?', 'ofke-krizleri-yasayan-cocuga-nasil-yaklasmaliyiz', 'Çocuk & Ebeveyn', 'Tantrum anlarında sakin kalabilmenin, çocuğun duygusunu onaylayıp sınır koyabilmenin nörobiyolojik temelleri.', '<p>Çocuklarda öfke krizleri ebeveyne karşı yapılmış bir kasıt değil, henüz olgunlaşmamış prefrontal korteksin taşmasıdır.</p>', 'assets/images/counselor.jpg', '4 dk', 95, 'published', '2026-10-25'),
(3, 'Evlilikte Duygusal Güven ve Bağlanma Stilleri', 'evlilikte-duygusal-guven-ve-baglanma-stilleri', 'Evlilik Terapisi', 'Kaygılı ve kaçıngan bağlanma döngüsünü fark etmek ve ilişkide güvenli bir liman inşa etmek.', '<p>Çocuklukta kurduğumuz ilişki modelleri yetişkinlikte partnerimize verdiğimiz tepkileri belirler.</p>', 'assets/images/seminar.jpg', '6 dk', 88, 'published', '2026-10-20')
ON DUPLICATE KEY UPDATE title = VALUES(title);

SET FOREIGN_KEY_CHECKS = 1;

<?php
/**
 * Otomatik Veritabanı Kurulum ve Örnek Veri Yükleme Aracı
 */

require_once __DIR__ . '/../config/config.php';

function initialize_database() {
    $db = get_db();

    if (DB_TYPE === 'sqlite') {
        // SQLite Tabloları
        $db->exec("
            CREATE TABLE IF NOT EXISTS settings (
                `key` TEXT PRIMARY KEY,
                `value` TEXT
            );

            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS services (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                category TEXT DEFAULT 'Danışmanlık',
                summary TEXT,
                content TEXT,
                duration TEXT DEFAULT '50 Dakika',
                price TEXT,
                image TEXT,
                is_active INTEGER DEFAULT 1,
                display_order INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS appointments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                service_id INTEGER,
                client_name TEXT NOT NULL,
                client_phone TEXT NOT NULL,
                client_email TEXT,
                appointment_date TEXT NOT NULL,
                appointment_time TEXT NOT NULL,
                notes TEXT,
                status TEXT DEFAULT 'pending',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS blocked_slots (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                block_date TEXT NOT NULL,
                block_time TEXT,
                reason TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS blog_posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                category TEXT DEFAULT 'Aile & İletişim',
                summary TEXT,
                content TEXT,
                cover_image TEXT,
                reading_time TEXT DEFAULT '4 dk',
                views INTEGER DEFAULT 0,
                status TEXT DEFAULT 'published',
                published_at TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT,
                subject TEXT,
                message TEXT NOT NULL,
                is_read INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");
    } else {
        // MySQL Tabloları
        $schema = file_get_contents(__DIR__ . '/schema.sql');
        $db->exec($schema);
    }

    // 1. Admin Kullanıcısı Kontrol & Ekle
    $stmt = $db->query("SELECT COUNT(*) as cnt FROM admins");
    $adminCount = $stmt->fetch()['cnt'];
    if ($adminCount == 0) {
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insAdmin = $db->prepare("INSERT INTO admins (username, password, name, email) VALUES (?, ?, ?, ?)");
        $insAdmin->execute(['admin', $defaultPassword, 'Yönetici', 'admin@example.com']);
    }

    // 2. Varsayılan Ayarlar
    $defaultSettings = [
        'site_title' => 'Uzm. Aile Danışmanı Çağla TOKYÜREK | Aile, Çift & Ebeveyn Danışmanlığı',
        'counselor_name' => 'Çağla TOKYÜREK',
        'counselor_title' => 'Uzm. Aile Danışmanı & Psikolojik Danışman',
        'eyebrow' => 'Aile & Çift Danışmanı, Eğitmen ve Konuşmacı',
        'hero_mission' => 'Aile içi dinamikleri güçlendiren, ebeveynlik yolculuğunuza rehberlik eden ve ilişkilerdeki tıkanıklıkları sevgi ve bilimsel temellerle çözen bütüncül bir yaklaşım.',
        'whatsapp_number' => '905551234567',
        'phone' => '+90 (555) 123 45 67',
        'email' => 'iletisim@caglatokyurek.com',
        'address' => 'Bağdat Caddesi No: 142/5 Kadıköy, İstanbul',
        'working_hours' => 'Pazartesi - Cumartesi: 10:00 - 18:30 (Pazar Kapalı)',
        'available_slots' => '10:00,11:15,13:00,14:15,15:30,16:45,18:00',
        'instagram' => 'https://instagram.com/caglatokyurek.aile',
        'youtube' => 'https://youtube.com/@caglatokyurek',
        'linkedin' => 'https://linkedin.com',
        'stat_experience' => '12+ Yıl',
        'stat_clients' => '2500+ Seans',
        'stat_seminars' => '40+ Seminer',
        'stat_followers' => '250K+',
        'about_text_short' => 'İstanbul Üniversitesi Psikolojik Danışmanlık ve Rehberlik lisansının ardından Aile Danışmanlığı alanında uzmanlığını tamamlayan Çağla TOKYÜREK, 12 yılı aşkın süredir çiftler, ebeveynler ve çocuklarla çalışmaktadır.',
        'about_text_full' => 'İstanbul Üniversitesi Psikolojik Danışmanlık ve Rehberlik Bölümü mezuniyetinin ardından Klinik Psikoloji ve Aile Danışmanlığı alanında yüksek lisansını tamamlamıştır. Gottman Çift Terapisi, Şema Terapi ve Deneyimsel Oyun Terapisi eğitimlerini uluslararası akreditasyonla tamamlayarak danışanlarına kanıta dayalı ve şefkatli bir terapi alanı sunmaktadır.

Türkiye genelinde yüz yüze ve online danışmanlık hizmeti sunmakta; kurucusu olduğu "Bilinçli Ebeveynlik", "Evlilikte Biz Olabilmek" ve "Ergenlikte İletişim Köprüleri" seminer dizileriyle binlerce anne-babaya ulaşmaktadır.'
    ];

    foreach ($defaultSettings as $key => $val) {
        $check = $db->prepare("SELECT COUNT(*) as c FROM settings WHERE `key` = ?");
        $check->execute([$key]);
        if ($check->fetch()['c'] == 0) {
            $ins = $db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)");
            $ins->execute([$key, $val]);
        }
    }

    // 3. Hizmetler & Eğitimler
    $stmt = $db->query("SELECT COUNT(*) as cnt FROM services");
    if ($stmt->fetch()['cnt'] == 0) {
        $services = [
            [
                'title' => 'Bireysel Aile Danışmanlığı',
                'slug' => 'bireysel-aile-danismanligi',
                'category' => 'Danışmanlık',
                'summary' => 'Aile bireyleri arasındaki iletişim engellerini aşmak ve aile içi dengeleri yeniden kurmak için profesyonel destek.',
                'content' => 'Aile içindeki roller, kuşaklararası aktarımlar, kriz anları ve iletişim kopuklukları üzerine odaklanılan seanslarda; her bireyin kendini güvende ve anlaşılmış hissedeceği bir alan oluşturulur.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/counselor.jpg',
                'display_order' => 1
            ],
            [
                'title' => 'Çift ve Evlilik Terapisi',
                'slug' => 'cift-ve-evlilik-terapisi',
                'category' => 'Danışmanlık',
                'summary' => 'Gottman yöntemi temelli, çiftlerin duygusal bağlarını onarmalarına ve çatışmaları yapıcı yönetmelerine rehberlik.',
                'content' => 'İlişkilerde tekrar eden kısırdöngüler, kırgınlıklar, güven sorunları ve ortak yaşam hedefleri yapılandırılmış seanslarla ele alınır. Birlikte sevgi ve saygıyla büyüyen bir ilişki inşa edilir.',
                'duration' => '60 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/therapy.jpg',
                'display_order' => 2
            ],
            [
                'title' => 'Bilinçli Ebeveynlik Atölyesi',
                'slug' => 'bilincli-ebeveynlik-atolyesi',
                'category' => 'Eğitim & Seminer',
                'summary' => 'Çocukların gelişim evrelerini anlama, kriz anlarında şefkatli sınırlar koyma ve pozitif disiplin eğitimi.',
                'content' => '4 haftalık modüler eğitim programı. Çocuklarda öfke nöbetleri, kardeş kıskançlığı, ekran bağımlılığı ve özgüven gelişimi konularında pratik uygulamalı atölye çalışması.',
                'duration' => '4 Hafta (Haftada 2 Saat)',
                'price' => 'Grup Eğitimi',
                'image' => 'assets/images/seminar.jpg',
                'display_order' => 3
            ],
            [
                'title' => 'Ergenlikte İletişim Köprüleri Semineri',
                'slug' => 'ergenlikte-iletisim-kopruleri',
                'category' => 'Eğitim & Seminer',
                'summary' => 'Ergenlik çağındaki gençlerin psikolojik dünyasını anlama ve çatışmasız sağlıklı iletişim kurma semineri.',
                'content' => 'Gençlerin kimlik arayışı, okul ve sınav kaygısı, aileyle çatışmalar ve dijital çağda ergenlik konuları interaktif vaka örnekleriyle ele alınır.',
                'duration' => 'Tek Oturum (2.5 Saat)',
                'price' => 'Canlı / Online',
                'image' => 'assets/images/seminar.jpg',
                'display_order' => 4
            ],
            [
                'title' => 'Boşanma Süreci ve Çocuk Rehberliği',
                'slug' => 'bosanma-sureci-ve-cocuk-rehberligi',
                'category' => 'Danışmanlık',
                'summary' => 'Ayrılık ve boşanma evresinde çocukların ruh sağlığını koruma, ortak ebeveynlik planı oluşturma desteği.',
                'content' => 'Eşler arasındaki bağ biterken ebeveynlik bağının sağlıklı sürdürülmesi ve çocukların bu süreci en az hasarla atlatması için özel yapılandırılmış danışmanlık.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/counselor.jpg',
                'display_order' => 5
            ]
        ];

        $insServ = $db->prepare("INSERT INTO services (title, slug, category, summary, content, duration, price, image, is_active, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)");
        foreach ($services as $s) {
            $insServ->execute([$s['title'], $s['slug'], $s['category'], $s['summary'], $s['content'], $s['duration'], $s['price'], $s['image'], $s['display_order']]);
        }
    }

    // 4. Örnek Blog Yazıları
    $stmt = $db->query("SELECT COUNT(*) as cnt FROM blog_posts");
    if ($stmt->fetch()['cnt'] == 0) {
        $posts = [
            [
                'title' => 'Çiftler Arasında Sağlıklı İletişim Kurmanın 5 Altın Kuralı',
                'slug' => 'ciftler-arasinda-saglikli-iletisim-kurmanin-5-altin-kurali',
                'category' => 'İlişki & Çift',
                'summary' => 'Tartışmaların kavgaya dönüşmesini engelleyen, birbirinizi gerçekten duymanızı sağlayan pratik iletişim prensipleri.',
                'content' => '<p>İlişkilerde çatışma kaçınılmazdır; ancak çatışmanın yıkıcı mı yoksa bağları güçlendirici mi olacağını belirleyen şey üslubumuzdur. Araştırmalar, mutlu çiftlerin hiç tartışmayanlar değil, tartışırken birbirine saygı ve nezaketle yaklaşabilenler olduğunu gösteriyor.</p><h3>1. "Sen" Dili Yerine "Ben" Dili Kullanın</h3><p>"Sen beni hiç dinlemiyorsun" yerine "Konuşurken dinlenmediğimi hissettiğimde üzülüyorum" demek, karşı tarafı savunmaya geçirmek yerine duygusal temas kurar.</p><h3>2. Haklı Çıkmaya Değil, Anlamaya Odaklanın</h3><p>Tartışmalar bir münazara değildir; kazananı ve kaybedeni olmaz. Ya iki taraf da anlaşılır ve kazanır, ya da iki taraf da uzaklaşır.</p><h3>3. Beden Dili ve Göz Teması</h3><p>Telefonu bir kenara bırakıp eşinizin gözlerine bakarak konuşmak, ona verdiğiniz değerin en somut göstergesidir.</p>',
                'cover_image' => 'assets/images/therapy.jpg',
                'reading_time' => '5 dk',
                'published_at' => date('Y-m-d')
            ],
            [
                'title' => 'Öfke Krizleri Yaşayan Çocuğa Nasıl Yaklaşmalıyız?',
                'slug' => 'ofke-krizleri-yasayan-cocuga-nasil-yaklasmaliyiz',
                'category' => 'Çocuk & Ebeveyn',
                'summary' => 'Tantrum anlarında sakin kalabilmenin, çocuğun duygusunu onaylayıp sınır koyabilmenin nörobiyolojik temelleri.',
                'content' => '<p>Çocuklarda öfke krizleri ebeveyne karşı yapılmış bir kasıt değil, henüz olgunlaşmamış prefrontal korteksin büyük duygular karşısında taşmasıdır. Çocuk o anda bizi değil, kendini regüle etme desteğini arar.</p><h3>Öfke Anında Güvenli Alan Yaratmak</h3><p>Çocuğa "Sakin ol!", "Ağlayacak ne var!" demek fırtınaya benzin dökmek gibidir. Bunun yerine "Çok öfkelendiğini görüyorum. Ben buradayım, sakinleşene kadar yanındayım" mesajı vermek sinir sistemini yatıştırır.</p>',
                'cover_image' => 'assets/images/counselor.jpg',
                'reading_time' => '4 dk',
                'published_at' => date('Y-m-d', strtotime('-3 days'))
            ],
            [
                'title' => 'Evlilikte Duygusal Güven ve Bağlanma Stilleri',
                'slug' => 'evlilikte-duygusal-guven-ve-baglanma-stilleri',
                'category' => 'Evlilik Terapisi',
                'summary' => 'Kaygılı ve kaçıngan bağlanma döngüsünü fark etmek ve ilişkide güvenli bir liman inşa etmek.',
                'content' => '<p>Çocuklukta ebeveynlerimizle kurduğumuz ilişki modelleri, yetişkinlikte partnerimize gösterdiğimiz tepkilerin haritasını çizer. Kimi eş kavga anında içine kapanır (kaçıngan), kimi ise peşinden koşarak hemen çözmek ister (kaygılı).</p><p>Bu döngüyü fark ettiğimizde, eşimizin tepkilerinin bize duyduğu sevgisizlikten değil, güvende hissetme çabasından kaynaklandığını anlarız.</p>',
                'cover_image' => 'assets/images/seminar.jpg',
                'reading_time' => '6 dk',
                'published_at' => date('Y-m-d', strtotime('-7 days'))
            ]
        ];

        $insPost = $db->prepare("INSERT INTO blog_posts (title, slug, category, summary, content, cover_image, reading_time, views, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, 120, 'published', ?)");
        foreach ($posts as $p) {
            $insPost->execute([$p['title'], $p['slug'], $p['category'], $p['summary'], $p['content'], $p['cover_image'], $p['reading_time'], $p['published_at']]);
        }
    }

    // 5. Test Randevusu (Dolu Tarih & Saat mekanizmasını göstermek için yarın gününe bir randevu ekleyelim)
    $stmt = $db->query("SELECT COUNT(*) as cnt FROM appointments");
    if ($stmt->fetch()['cnt'] == 0) {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $insApp = $db->prepare("INSERT INTO appointments (service_id, client_name, client_phone, client_email, appointment_date, appointment_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insApp->execute([1, 'Ahmet Yılmaz', '05559876543', 'ahmet@example.com', $tomorrow, '11:15', 'Aile içi iletişim danışmanlığı', 'confirmed']);
    }

    return true;
}

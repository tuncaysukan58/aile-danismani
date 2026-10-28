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
        'site_title' => 'Çağla Tokyürek — Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı',
        'counselor_name' => 'Çağla Tokyürek',
        'counselor_title' => 'Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı',
        'eyebrow' => 'Çocuk Gelişimi | Aile, Çift ve Boşanma Danışmanlığı',
        'hero_mission' => 'Boşanma sürecinde çift ve çocuk odaklı rehberlik, aile içi ilişki dinamikleri ve çocuk gelişimi alanında bilimsel, şefkatli ve profesyonel danışmanlık.',
        'whatsapp_number' => '@caglatokyurek',
        'phone' => '+90 (555) 123 45 67',
        'email' => 'curlycagla@gmail.com',
        'address' => 'Kadıköy, İstanbul',
        'working_hours' => 'Pazartesi - Cumartesi: 10:00 - 18:30 (Pazar Kapalı)',
        'available_slots' => '10:00,11:15,13:00,14:15,15:30,16:45,18:00',
        'instagram' => 'https://instagram.com/caglatokyurek',
        'youtube' => 'https://youtube.com/@caglatokyurek',
        'linkedin' => 'https://linkedin.com',
        'stat_experience' => '8+ Yıl',
        'stat_clients' => '1500+ Seans',
        'stat_seminars' => '30+ Eğitim',
        'stat_followers' => '50K+',
        'about_text_short' => 'Atatürk Üniversitesi Çocuk Gelişimi lisans mezunu olan ve akademik çalışmalarını psikoloji alanında sürdüren Çağla Tokyürek; İstanbul Arel Üniversitesi Aile Danışmanlığı uzmanlığıyla boşanma sürecindeki çiftler, boşanmış aileler ve çocuklarla profesyonel danışmanlık süreçleri yürütmektedir.',
        'about_text_full' => "Atatürk Üniversitesi Çocuk Gelişimi Bölümü lisans mezunu olan Çağla Tokyürek, akademik çalışmalarını psikoloji alanında sürdürmektedir. İstanbul Arel Üniversitesi'nde Aile Danışmanlığı eğitimini tamamlamıştır.\n\nÖzellikle boşanma alanında yoğunlaşarak; boşanma aşamasındaki çiftler, boşanmış ya da boşanmaya karar veren ailelerle ve bu süreçteki çocuklarıyla profesyonel çalışmalar yürütmektedir. Sürecin sağlıklı yürütülmesi, ebeveynlik iş birliğinin korunması ve çocukların gelişimsel ihtiyaçlarının gözetilmesi temel önceliğidir.\n\nTamamladığı uzmanlık eğitimleri arasında Boşanma Danışmanlığı, Yetişkin, Ergen ve Çocuk Psikolojisi Eğitimleri, Çocuk Değerlendirme Testleri, Oyun Terapisi, Çocuk Resimlerinin Psiko-Pedagojik Analizi (Resim Analizi), MOXO Dikkat Testi, Denver II Gelişimsel Tarama Testi ve Eğitim Danışmanlığı yer almaktadır."
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
                'title' => 'Boşanma Danışmanlığı & Çocuk Rehberliği',
                'slug' => 'bosanma-danismanligi',
                'category' => 'Boşanma Danışmanlığı',
                'summary' => 'Boşanma aşamasındaki çiftler, boşanmış aileler ve bu süreçteki çocukların psikolojik dengesini koruyan uzman rehberlik.',
                'content' => 'Boşanma sürecine karar verme, ayrılık sürecini çocuklara açıklama, velayet ve ortak ebeveynlik planı oluşturma, boşanma sonrası yeni yaşam düzenine uyum süreçlerinde bilimsel ve şefkatli danışmanlık sunulur.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/counselor.jpg',
                'display_order' => 1
            ],
            [
                'title' => 'Aile ve Çift Danışmanlığı',
                'slug' => 'aile-ve-cift-danismanligi',
                'category' => 'Aile & Çift',
                'summary' => 'Eşler arası iletişim çatışmalarını çözme, ilişki bağlarını güçlendirme ve aile içi krizleri yönetme desteği.',
                'content' => 'İlişkilerde tekrar eden kısırdöngüler, iletişim engelleri, duygusal kopukluklar ve aile içi uyum problemleri ele alınır. Çiftlerin birbirini yargılamadan dinleyebileceği güvenli bir danışmanlık alanı sağlanır.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/therapy.jpg',
                'display_order' => 2
            ],
            [
                'title' => 'Çocuk Değerlendirme & Gelişim Testleri',
                'slug' => 'cocuk-degerlendirme-testleri',
                'category' => 'Çocuk Gelişimi',
                'summary' => 'Denver II Gelişimsel Tarama Testi ve Çocuk Değerlendirme Testleri ile bilişsel, motor ve sosyal gelişim takibi.',
                'content' => '0-6 yaş ve okul dönemi çocukların gelişim evreleri standart bilimsel test bataryaları ile taranır. Olası gelişimsel gerilikler veya güçlü yönler tespit edilerek aileye bireysel gelişim haritası çıkarılır.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze Seans',
                'image' => 'assets/images/seminar.jpg',
                'display_order' => 3
            ],
            [
                'title' => 'MOXO Dikkat Testi Uygulaması',
                'slug' => 'moxo-dikkat-testi',
                'category' => 'Test & Değerlendirme',
                'summary' => 'Çocuk ve ergenlerde dikkat, zamanlama, dürtüsellik ve hiperaktivite düzeyini ölçen uluslararası geçerli test.',
                'content' => 'Görsel ve işitsel çeldiriciler eşliğinde bilgisayar ortamında uygulanan MOXO Dikkat Testi, çocuğun dikkat performansını ve çevresel uyaranlara tepkisini objektif olarak raporlar.',
                'duration' => '45 Dakika',
                'price' => 'Yüz Yüze Seans',
                'image' => 'assets/images/seminar.jpg',
                'display_order' => 4
            ],
            [
                'title' => 'Oyun Terapisi & Çocuk Resimleri Analizi',
                'slug' => 'oyun-terapisi-ve-resim-analizi',
                'category' => 'Çocuk Terapisi',
                'summary' => 'Oyun terapisi ve çocuk resimlerinin psiko-pedagojik analizi ile çocuğun iç dünyasını anlama ve duygusal iyileşme.',
                'content' => 'Çocuklar duygularını yetişkinler gibi sözcüklerle değil, oyun ve çizim yoluyla ifade eder. Oyun terapisi ve resim analiziyle kaygı, korku, öfke ve travma süreçleri güvenli bir ortamda çözümlenir.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze Seans',
                'image' => 'assets/images/counselor.jpg',
                'display_order' => 5
            ],
            [
                'title' => 'Eğitim Danışmanlığı & Ebeveyn Rehberliği',
                'slug' => 'egitim-danismanligi-ve-ebeveyn-rehberligi',
                'category' => 'Eğitim Danışmanlığı',
                'summary' => 'Okul uyumu, çalışma alışkanlıkları, ergenlik dönemi iletişimi ve bilinçli ebeveynlik desteği.',
                'content' => 'Okul başarısı, sınav dönemi kaygıları, ergenlikte ebeveyn-çocuk iletişimi ve sınır koyma dinamikleri üzerine ailelere özel yapılandırılmış rehberlik.',
                'duration' => '50 Dakika',
                'price' => 'Yüz Yüze / Online',
                'image' => 'assets/images/therapy.jpg',
                'display_order' => 6
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

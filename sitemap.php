<?php
/**
 * Dinamik XML Sitemap
 * Erişim: /sitemap.xml (.htaccess ile bu dosyaya yönlendirilir)
 * Statik sayfalar + yayındaki blog yazıları + aktif eğitim/hizmet sayfaları
 */
require_once __DIR__ . '/config/config.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex'); // Sitemap dosyasının kendisi arama sonucunda çıkmasın

$base = rtrim(SITE_URL, '/');

/** Dosya değişiklik tarihini W3C formatında döndürür */
function file_lastmod(string $file): string {
    $path = __DIR__ . '/' . $file;
    return date('Y-m-d', is_file($path) ? filemtime($path) : time());
}

/** Veritabanı tarihini (Y-m-d veya Y-m-d H:i:s) W3C formatına çevirir */
function db_lastmod(?string $date): string {
    $ts = $date ? strtotime($date) : false;
    return date('Y-m-d', $ts ?: time());
}

$urls = [
    ['loc' => $base . '/',          'lastmod' => file_lastmod('index.php'),     'changefreq' => 'weekly',  'priority' => '1.0'],
    ['loc' => $base . '/hakkimda',  'lastmod' => file_lastmod('hakkimda.php'),  'changefreq' => 'monthly', 'priority' => '0.9'],
    ['loc' => $base . '/egitimler', 'lastmod' => file_lastmod('egitimler.php'), 'changefreq' => 'weekly',  'priority' => '0.9'],
    ['loc' => $base . '/randevu',   'lastmod' => file_lastmod('randevu.php'),   'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => $base . '/blog',      'lastmod' => file_lastmod('blog.php'),      'changefreq' => 'weekly',  'priority' => '0.8'],
    ['loc' => $base . '/iletisim',  'lastmod' => file_lastmod('iletisim.php'),  'changefreq' => 'yearly',  'priority' => '0.7'],
    ['loc' => $base . '/kvkk',      'lastmod' => file_lastmod('kvkk.php'),      'changefreq' => 'yearly',  'priority' => '0.3'],
];

try {
    $db = get_db();

    // Aktif eğitim / hizmet sayfaları
    $services = $db->query("SELECT slug, created_at FROM services WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($services as $s) {
        $urls[] = [
            'loc'        => $base . '/egitim/' . rawurlencode($s['slug']),
            'lastmod'    => db_lastmod($s['created_at'] ?? null),
            'changefreq' => 'monthly',
            'priority'   => '0.8',
        ];
    }

    // Yayındaki blog yazıları
    $posts = $db->query("SELECT slug, published_at, created_at FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($posts as $p) {
        $urls[] = [
            'loc'        => $base . '/blog/' . rawurlencode($p['slug']),
            'lastmod'    => db_lastmod($p['published_at'] ?: ($p['created_at'] ?? null)),
            'changefreq' => 'monthly',
            'priority'   => '0.7',
        ];
    }
} catch (Throwable $e) {
    // Veritabanı hatası olsa bile statik sayfalarla geçerli bir sitemap döndür
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
    echo '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
    echo '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
    echo '    <priority>' . $u['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";

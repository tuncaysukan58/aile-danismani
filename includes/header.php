<?php
if (!defined('SITE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}

$counselorName = get_setting('counselor_name', 'Çağla Tokyürek');
$counselorTitle = get_setting('counselor_title', 'Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı');
$siteTitle = get_setting('site_title', $counselorName . ' — Çocuk Gelişimci & Aile, Çift ve Boşanma Danışmanı');
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="tr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $siteTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Aile, çift ve ebeveyn danışmanlığı, atölyeler ve seminerler. Güvenli ve profesyonel rehberlik.') ?>">
    <link rel="canonical" href="<?= $canonicalUrl ?? (SITE_URL . '/' . ($currentPage === 'index' ? '' : $currentPage)) ?>">
<?php
    // --- SEO: Arama motoru ve sosyal medya etiketleri ---
    $seoTitle = $pageTitle ?? $siteTitle;
    $seoDesc  = $metaDescription ?? 'Aile, çift ve boşanma danışmanlığı, çocuk gelişimi değerlendirmeleri. Güvenli ve profesyonel rehberlik.';
    $seoUrl   = $canonicalUrl ?? (SITE_URL . '/' . ($currentPage === 'index' ? '' : $currentPage));
    $seoImage = $ogImage ?? (SITE_URL . '/assets/images/logo.jpg');
    $gsv      = get_setting('google_site_verification', '');
?>
    <meta name="robots" content="<?= !empty($noIndex) ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1' ?>">
    <meta name="author" content="<?= htmlspecialchars($counselorName) ?>">
    <?php if ($gsv !== ''): ?>
    <meta name="google-site-verification" content="<?= htmlspecialchars($gsv) ?>">
    <?php endif; ?>

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:type" content="<?= htmlspecialchars($ogType ?? 'website') ?>">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:site_name" content="<?= htmlspecialchars($counselorName) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seoDesc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($seoUrl) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seoImage) ?>">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($seoTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seoDesc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($seoImage) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_URL ?>/assets/images/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_URL ?>/assets/images/apple-touch-icon.png">
    <meta name="theme-color" content="#855838">

    <?php if ($currentPage === 'index'): ?>
    <!-- Yapılandırılmış Veri (Google zengin sonuçlar) -->
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id'   => SITE_URL . '/#website',
                'url'   => SITE_URL . '/',
                'name'  => $counselorName,
                'inLanguage' => 'tr-TR',
            ],
            [
                '@type' => 'Person',
                '@id'   => SITE_URL . '/#person',
                'name'  => $counselorName,
                'jobTitle' => $counselorTitle,
                'url'   => SITE_URL . '/',
                'image' => SITE_URL . '/assets/images/counselor.jpg',
                'email' => 'mailto:' . get_setting('email', 'curlycagla@gmail.com'),
                'alumniOf' => [
                    ['@type' => 'CollegeOrUniversity', 'name' => 'Atatürk Üniversitesi'],
                    ['@type' => 'CollegeOrUniversity', 'name' => 'İstanbul Arel Üniversitesi'],
                ],
                'knowsAbout' => ['Boşanma Danışmanlığı', 'Aile Danışmanlığı', 'Çift Danışmanlığı', 'Çocuk Gelişimi', 'Oyun Terapisi', 'MOXO Dikkat Testi', 'Denver II Gelişimsel Tarama Testi'],
            ],
            [
                '@type' => 'ProfessionalService',
                '@id'   => SITE_URL . '/#service',
                'name'  => $counselorName . ' — ' . $counselorTitle,
                'url'   => SITE_URL . '/',
                'image' => SITE_URL . '/assets/images/logo.jpg',
                'logo'  => SITE_URL . '/assets/images/logo.jpg',
                'email' => get_setting('email', 'curlycagla@gmail.com'),
                'founder' => ['@id' => SITE_URL . '/#person'],
                'areaServed' => ['@type' => 'Country', 'name' => 'Türkiye'],
                'serviceType' => ['Boşanma Danışmanlığı', 'Aile ve Çift Danışmanlığı', 'Çocuk Gelişimi Değerlendirmesi', 'Oyun Terapisi'],
            ],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
    </script>
    <?php endif; ?>

    <!-- Google Fonts: Lora + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css?v=<?= @filemtime(__DIR__ . '/../assets/css/style.css') ?>">
    <?php if ($currentPage === 'randevu'): ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/appointments.css?v=<?= @filemtime(__DIR__ . '/../assets/css/appointments.css') ?>">
    <?php endif; ?>

    <script>
        window.SITE_URL = '<?= SITE_URL ?>';
    </script>

    <!-- Theme Init -->
    <script>
        (function() {
            try {
                var saved = localStorage.getItem('theme-mode');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute('data-theme', saved || (prefersDark ? 'dark' : 'light'));
            } catch(e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
</head>
<body>

<!-- Header / Navigation -->
<header class="site-header">
    <div class="container header-inner">
        <a href="<?= SITE_URL ?>/" class="brand-logo">
            <img src="<?= SITE_URL ?>/assets/images/logo-mark.png" alt="Çağla Tokyürek Logo" class="brand-logo-img" width="85" height="74">
            <div class="brand-logo-text">
                <span class="brand-name"><?= htmlspecialchars($counselorName) ?></span>
                <span class="brand-badge"><?= htmlspecialchars($counselorTitle) ?></span>
            </div>
        </a>

        <nav class="nav-desktop">
            <ul class="nav-menu">
                <li><a href="<?= SITE_URL ?>/" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">Anasayfa</a></li>
                <li><a href="<?= SITE_URL ?>/hakkimda" class="nav-link <?= $currentPage === 'hakkimda' ? 'active' : '' ?>">Hakkımda</a></li>
                <li><a href="<?= SITE_URL ?>/egitimler" class="nav-link <?= in_array($currentPage, ['egitimler', 'egitim-detay']) ? 'active' : '' ?>">Eğitimlerim</a></li>
                <li><a href="<?= SITE_URL ?>/blog" class="nav-link <?= in_array($currentPage, ['blog', 'blog-detay']) ? 'active' : '' ?>">Blog</a></li>
                <li><a href="<?= SITE_URL ?>/iletisim" class="nav-link <?= $currentPage === 'iletisim' ? 'active' : '' ?>">İletişim</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <!-- Dark / Light Mode Toggle -->
            <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Görünüm Modunu Değiştir">
                <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"></circle>
                    <path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                </svg>
                <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

            <!-- Randevu Butonu -->
            <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary header-cta <?= $currentPage === 'randevu' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Randevu Al</span>
            </a>

            <!-- Mobil Menü Butonu -->
            <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menüyü Aç">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- Mobil Menü Çekmecesi -->
    <div class="mobile-drawer" id="mobileDrawer">
        <ul class="mobile-menu-list">
            <li><a href="<?= SITE_URL ?>/" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Anasayfa</a></li>
            <li><a href="<?= SITE_URL ?>/hakkimda" class="<?= $currentPage === 'hakkimda' ? 'active' : '' ?>">Hakkımda</a></li>
            <li><a href="<?= SITE_URL ?>/egitimler" class="<?= $currentPage === 'egitimler' ? 'active' : '' ?>">Eğitimlerim</a></li>
            <li><a href="<?= SITE_URL ?>/blog" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a></li>
            <li><a href="<?= SITE_URL ?>/iletisim" class="<?= $currentPage === 'iletisim' ? 'active' : '' ?>">İletişim</a></li>
            <li class="mobile-cta-item">
                <a href="<?= SITE_URL ?>/randevu" class="btn btn-primary btn-block">Randevu Al</a>
            </li>
        </ul>
    </div>
</header>
<main class="main-content">

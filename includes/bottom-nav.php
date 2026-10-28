<?php
/**
 * Mobil Cihazlar İçin Alt Navigasyon Çubuğu (Bottom Navigation Bar)
 */
$curPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<nav class="mobile-bottom-nav" aria-label="Mobil Hızlı Menü">
    <a href="<?= SITE_URL ?>/" class="bottom-nav-item <?= $curPage === 'index' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        <span>Anasayfa</span>
    </a>

    <a href="<?= SITE_URL ?>/egitimler" class="bottom-nav-item <?= in_array($curPage, ['egitimler', 'egitim-detay']) ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>
        <span>Eğitimler</span>
    </a>

    <a href="<?= SITE_URL ?>/randevu" class="bottom-nav-item bottom-nav-highlight <?= $curPage === 'randevu' ? 'active' : '' ?>">
        <div class="nav-highlight-circle">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <span>Randevu</span>
    </a>

    <a href="<?= SITE_URL ?>/blog" class="bottom-nav-item <?= in_array($curPage, ['blog', 'blog-detay']) ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Blog</span>
    </a>

    <a href="<?= SITE_URL ?>/iletisim" class="bottom-nav-item <?= $curPage === 'iletisim' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
        </svg>
        <span>İletişim</span>
    </a>
</nav>

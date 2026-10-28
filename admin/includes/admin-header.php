<?php
require_once __DIR__ . '/auth.php';
$activePage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($adminTitle ?? 'Yönetim Paneli') ?> — Aile Danışmanı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
</head>
<body class="admin-body">

<!-- Sidebar -->
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <h3><?= htmlspecialchars(get_setting('counselor_name', 'Elif Karaca')) ?></h3>
        <span>Yönetim Sistemi</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="<?= ADMIN_URL ?>/index.php" class="<?= $activePage === 'index' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Gösterge Paneli</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/randevular.php" class="<?= $activePage === 'randevular' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <span>Randevu Yönetimi</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/blokaj.php" class="<?= $activePage === 'blokaj' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                <span>Tarih / Saat Kapatma</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/egitimler.php" class="<?= in_array($activePage, ['egitimler', 'egitim-duzenle']) ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span>Eğitimler & Hizmetler</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/blog.php" class="<?= in_array($activePage, ['blog', 'blog-duzenle']) ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span>Blog & Makaleler</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/mesajlar.php" class="<?= $activePage === 'mesajlar' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Gelen Mesajlar</span>
            </a>
        </li>
        <li>
            <a href="<?= ADMIN_URL ?>/ayarlar.php" class="<?= $activePage === 'ayarlar' ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Site Ayarları</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="<?= ADMIN_URL ?>/logout.php">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            <span>Çıkış Yap</span>
        </a>
    </div>
</aside>

<!-- İçerik Alanı -->
<div class="admin-main">
    <header class="admin-topbar">
        <div class="admin-topbar-title">
            <h2><?= htmlspecialchars($adminTitle ?? 'Yönetim Paneli') ?></h2>
        </div>
        <div class="admin-topbar-actions">
            <a href="<?= SITE_URL ?>/" target="_blank" class="btn-view-site">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>Siteyi Görüntüle</span>
            </a>
            <div style="font-size: 0.85rem; color: #64748B;">
                Giriş: <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Yönetici') ?></strong>
            </div>
        </div>
    </header>

    <div class="admin-content">

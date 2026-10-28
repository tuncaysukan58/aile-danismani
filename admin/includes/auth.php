<?php
/**
 * Admin Yetkilendirme Kontrolü
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';

if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

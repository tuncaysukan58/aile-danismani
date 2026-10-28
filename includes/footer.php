</main>

<footer class="site-footer">
    <div class="container footer-top">
        <div class="footer-grid">
            <!-- Kolon 1: Marka & Biyo -->
            <div class="footer-col footer-about">
                <h3 class="footer-brand"><?= htmlspecialchars(get_setting('counselor_name', 'Elif Karaca')) ?></h3>
                <p class="footer-title"><?= htmlspecialchars(get_setting('counselor_title', 'Uzm. Aile Danışmanı & Psikolojik Danışman')) ?></p>
                <p class="footer-desc">
                    <?= htmlspecialchars(get_setting('about_text_short', 'Aile içi ilişkiler, evlilik danışmanlığı ve ebeveyn rehberliği alanında bilimsel ve şefkatli yaklaşım.')) ?>
                </p>
                <div class="footer-socials">
                    <?php if ($ig = get_setting('instagram')): ?>
                    <a href="<?= htmlspecialchars($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($yt = get_setting('youtube')): ?>
                    <a href="<?= htmlspecialchars($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($li = get_setting('linkedin')): ?>
                    <a href="<?= htmlspecialchars($li) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolon 2: Hızlı Bağlantılar -->
            <div class="footer-col">
                <h4 class="footer-heading">Hızlı Erişim</h4>
                <ul class="footer-links">
                    <li><a href="<?= SITE_URL ?>/">Anasayfa</a></li>
                    <li><a href="<?= SITE_URL ?>/hakkimda">Hakkımda & Deneyim</a></li>
                    <li><a href="<?= SITE_URL ?>/egitimler">Eğitimler & Seminerler</a></li>
                    <li><a href="<?= SITE_URL ?>/randevu">Online / Yüz Yüze Randevu</a></li>
                    <li><a href="<?= SITE_URL ?>/blog">Makaleler & Yazılar</a></li>
                    <li><a href="<?= SITE_URL ?>/iletisim">İletişim & Konum</a></li>
                </ul>
            </div>

            <!-- Kolon 3: Hizmetler -->
            <div class="footer-col">
                <h4 class="footer-heading">Çalışma Alanları</h4>
                <ul class="footer-links">
                    <li><a href="<?= SITE_URL ?>/egitimler">Bireysel Aile Danışmanlığı</a></li>
                    <li><a href="<?= SITE_URL ?>/egitimler">Çift ve Evlilik Terapisi</a></li>
                    <li><a href="<?= SITE_URL ?>/egitimler">Bilinçli Ebeveynlik Atölyesi</a></li>
                    <li><a href="<?= SITE_URL ?>/egitimler">Ergenlikte İletişim Eğitimi</a></li>
                    <li><a href="<?= SITE_URL ?>/egitimler">Boşanma ve Çocuk Rehberliği</a></li>
                </ul>
            </div>

            <!-- Kolon 4: İletişim Bilgileri -->
            <div class="footer-col">
                <h4 class="footer-heading">İletişim Bilgileri</h4>
                <div class="footer-contact-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span><?= htmlspecialchars(get_setting('address', 'Kadıköy, İstanbul')) ?></span>
                </div>
                <div class="footer-contact-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span><?= htmlspecialchars(get_setting('phone', '+90 555 123 45 67')) ?></span>
                </div>
                <div class="footer-contact-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span><?= htmlspecialchars(get_setting('email', 'iletisim@elifkaraca.com')) ?></span>
                </div>
                <div class="footer-contact-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span><?= htmlspecialchars(get_setting('working_hours', 'Pzt - Cts: 10:00 - 18:30')) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>© <?= date('Y') ?> <?= htmlspecialchars(get_setting('counselor_name', 'Elif Karaca')) ?> — Tüm Hakları Saklıdır.</p>
            <div class="footer-bottom-links">
                <a href="<?= SITE_URL ?>/admin/login.php" class="admin-link">Yönetici Paneli</a>
            </div>
        </div>
    </div>
</footer>

<?php include __DIR__ . '/bottom-nav.php'; ?>
<?php include __DIR__ . '/whatsapp-widget.php'; ?>

<!-- Main JS -->
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
<?php if ($currentPage === 'randevu'): ?>
<script src="<?= SITE_URL ?>/assets/js/appointment.js"></script>
<?php endif; ?>

</body>
</html>

/**
 * Ana JavaScript Dosyası (Tema Değişimi, Mobil Menü, Animasyonlar)
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Dark / Light Tema Değişimi
    const themeBtn = document.getElementById('themeToggleBtn');
    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', nextTheme);
            localStorage.setItem('theme-mode', nextTheme);
        });
    }

    // 2. Mobil Menü Aç/Kapat
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    if (mobileBtn && mobileDrawer) {
        mobileBtn.addEventListener('click', function () {
            mobileDrawer.classList.toggle('active');
            const spans = mobileBtn.querySelectorAll('span');
            if (mobileDrawer.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }

    // 3. Header Scroll Efekti
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 30) {
                header.style.boxShadow = 'var(--shadow-md)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    }
});

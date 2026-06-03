import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initMobileMenu();
});

function initHeroSlider() {
    const slider = document.getElementById('hero-slider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('[data-hero-dot]');
    const prev = document.querySelector('[data-hero-prev]');
    const next = document.querySelector('[data-hero-next]');
    let current = 0;
    let timer;

    const show = (index) => {
        current = (index + slides.length) % slides.length;
        slides.forEach((s, i) => s.classList.toggle('active', i === current));
        dots.forEach((d, i) => {
            d.classList.toggle('bg-gold', i === current);
            d.classList.toggle('bg-white/50', i !== current);
        });
    };

    const nextSlide = () => show(current + 1);

    timer = setInterval(nextSlide, 5500);

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(timer);
            show(i);
            timer = setInterval(nextSlide, 5500);
        });
    });

    prev?.addEventListener('click', () => {
        clearInterval(timer);
        show(current - 1);
        timer = setInterval(nextSlide, 5500);
    });

    next?.addEventListener('click', () => {
        clearInterval(timer);
        show(current + 1);
        timer = setInterval(nextSlide, 5500);
    });

    show(0);
}

function initMobileMenu() {
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (!btn || !menu) return;

    btn.addEventListener('click', () => {
        const isOpen = !menu.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', String(isOpen));
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('site-header');

    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                header.classList.add('glass', 'shadow-lg');
            } else {
                header.classList.remove('glass', 'shadow-lg');
            }
        });
    }

    const mobileBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    initScrollShowcase();
});

function initScrollShowcase() {
    const showcase = document.getElementById('scroll-showcase');
    if (!showcase) return;

    const panels = Array.from(showcase.querySelectorAll('.showcase-panel'));
    if (panels.length < 2) return;

    function update() {
        const vh = window.innerHeight;

        panels.forEach(function (panel, i) {
            const image = panel.querySelector('.showcase-image');
            const content = panel.querySelector('.showcase-content');
            const overlay = panel.querySelector('.showcase-overlay') || panel.querySelector('.showcase-last-overlay');
            const cta = panel.querySelector('.showcase-cta');

            const rect = panel.getBoundingClientRect();
            const isLast = i === panels.length - 1;

            let progress = 0;

            if (i < panels.length - 1) {
                const next = panels[i + 1];
                const nextRect = next.getBoundingClientRect();
                progress = Math.min(Math.max(1 - (nextRect.top / vh), 0), 1);
            } else {
                progress = 0;
            }

            if (image) {
                image.style.transform = 'scale(' + (1 + progress * 0.15) + ')';
                image.style.filter = 'blur(' + (progress * 16) + 'px) brightness(' + (1 - progress * 0.5) + ')';
            }

            if (overlay) {
                overlay.style.opacity = 1 + progress * 0.5;
            }

            if (content) {
                content.style.opacity = 1 - progress * 1.5;
                content.style.transform = 'translateY(' + (progress * 60) + 'px)';
            }

            if (isLast && cta) {
                const lastImage = panel.querySelector('.showcase-last-image');
                const ctaRect = panel.getBoundingClientRect();
                const ctaProgress = Math.min(Math.max(-ctaRect.top / (vh * 0.5), 0), 1);

                if (lastImage) {
                    lastImage.style.transform = 'scale(' + (1 + ctaProgress * 0.15) + ')';
                }
                cta.style.opacity = ctaProgress;
                cta.style.transform = 'translateY(' + ((1 - ctaProgress) * 40) + 'px)';
            }
        });
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
}

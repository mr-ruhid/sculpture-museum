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

    const panels = showcase.querySelectorAll('.showcase-panel');
    if (!panels.length) return;

    const lastPanel = panels[panels.length - 1];
    const lastCta = lastPanel.querySelector('.showcase-cta');
    const lastOverlay = lastPanel.querySelector('.showcase-overlay');
    const lastImage = lastPanel.querySelector('.showcase-image');

    function update() {
        panels.forEach(function (panel, i) {
            const content = panel.querySelector('.showcase-content');
            const rect = panel.getBoundingClientRect();
            const progress = Math.min(Math.max(-rect.top / window.innerHeight, 0), 1);

            if (content) {
                content.style.transform = 'translateY(' + (progress * 80) + 'px)';
                content.style.opacity = 1 - progress * 0.6;
            }
        });

        const rect = lastPanel.getBoundingClientRect();
        const trigger = -rect.top - window.innerHeight * 0.5;

        if (trigger > 0) {
            const intensity = Math.min(trigger / (window.innerHeight * 0.6), 1);

            if (lastImage) {
                lastImage.style.filter = 'blur(' + (intensity * 20) + 'px)';
                lastImage.style.transform = 'scale(' + (1 + intensity * 0.1) + ')';
            }
            if (lastOverlay) {
                lastOverlay.style.opacity = 1 - intensity * 0.3;
            }
            if (lastCta) {
                lastCta.style.opacity = intensity;
                lastCta.style.pointerEvents = intensity > 0.5 ? 'auto' : 'none';
            }
        } else {
            if (lastImage) lastImage.style.filter = 'blur(0px)';
            if (lastCta) lastCta.style.opacity = 0;
        }
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
}

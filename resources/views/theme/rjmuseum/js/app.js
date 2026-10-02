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

/**
 * Scroll-driven showcase.
 *
 * One sticky "stage" holds every slide stacked on top of each other.
 * The scroll position inside the tall section is converted into a
 * floating point value `pos` (0 .. slides-1). The integer part is the
 * current slide, the fraction is the transition to the next one.
 *
 * Transitions alternate between:
 *   - a circular reveal rising from the bottom
 *   - a rounded wipe coming in from the right
 * Titles are revealed word by word, the details fade up in sequence,
 * a big ghost number slides with parallax, and the previous slide
 * zooms out and dims while it is covered.
 */
function initScrollShowcase() {
    const root = document.getElementById('scroll-showcase');
    if (!root) return;

    const slideEls = Array.from(root.querySelectorAll('.sc-slide'));
    const S = slideEls.length;
    if (S < 2) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const clamp = function (v, a, b) {
        return Math.min(Math.max(v, a === undefined ? 0 : a), b === undefined ? 1 : b);
    };
    const easeOut = function (t) { return 1 - Math.pow(1 - t, 3); };
    const easeInOut = function (t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    };
    const pad = function (n) { return n < 10 ? '0' + n : String(n); };

    // --- Split titles into words (text stays readable for screen readers) ---
    root.querySelectorAll('[data-split]').forEach(function (el) {
        const text = el.textContent.trim();
        el.setAttribute('aria-label', text);
        el.textContent = '';

        text.split(/\s+/).forEach(function (word, idx, arr) {
            const outer = document.createElement('span');
            outer.className = 'sc-word';
            outer.setAttribute('aria-hidden', 'true');

            const inner = document.createElement('span');
            inner.textContent = word;
            outer.appendChild(inner);
            el.appendChild(outer);

            if (idx < arr.length - 1) {
                el.appendChild(document.createTextNode(' '));
            }
        });
    });

    root.classList.add('sc-ready');

    // --- Cache elements ---
    const items = slideEls.map(function (el, i) {
        return {
            el: el,
            i: i,
            mode: i % 2 === 0 ? 'inset' : 'circle',
            image: el.querySelector('.sc-image'),
            dim: el.querySelector('.sc-dim'),
            ghost: el.querySelector('.sc-ghost'),
            content: el.querySelector('.sc-content'),
            words: Array.from(el.querySelectorAll('.sc-word > span')),
            reveals: Array.from(el.querySelectorAll('[data-reveal]'))
        };
    });

    const bar = root.querySelector('.sc-progress > span');
    const dots = Array.from(root.querySelectorAll('.sc-dot'));
    const counter = root.querySelector('.sc-counter');
    const currentLabel = root.querySelector('.sc-current');

    // --- State ---
    let target = 0;       // where the scroll says we should be
    let current = 0;      // smoothed value that is actually rendered
    let introT = reduceMotion ? 1 : 0;
    let introStart = null;
    let rafId = null;

    function readTarget() {
        const rect = root.getBoundingClientRect();
        const total = root.offsetHeight - window.innerHeight;
        if (total <= 0) { target = 0; return; }

        const raw = clamp(-rect.top / total) * (S - 1);
        const base = Math.min(Math.floor(raw), S - 1);
        const frac = raw - base;

        // Hold each slide for a moment, then transition in the middle part
        target = base + easeInOut(clamp((frac - 0.12) / 0.76));
    }

    function render(pos) {
        const intro = easeOut(introT);

        items.forEach(function (it) {
            const d = pos - it.i;

            if (d <= -1 || d >= 1) {
                it.el.style.visibility = 'hidden';
                it.el.style.pointerEvents = 'none';
                return;
            }

            it.el.style.visibility = 'visible';
            it.el.style.pointerEvents = Math.abs(d) < 0.5 ? 'auto' : 'none';

            const enter = it.i === 0 ? intro : clamp(1 + d);  // 0 -> 1 while the slide appears
            const exit = clamp(d);                            // 0 -> 1 while it is being covered

            // --- Clip-path reveal ---
            let clip = 'none';
            if (it.i > 0 && enter < 1) {
                if (it.mode === 'circle') {
                    clip = 'circle(' + (enter * 150).toFixed(2) + '% at 50% 100%)';
                } else {
                    const r = ((1 - enter) * 60).toFixed(1);
                    clip = 'inset(0 0 0 ' + ((1 - enter) * 100).toFixed(2) + '% round ' + r + 'px 0 0 ' + r + 'px)';
                }
            }
            it.el.style.clipPath = clip;
            it.el.style.webkitClipPath = clip;

            // --- Image: zoom + parallax ---
            if (it.image) {
                const scale = (1.3 - 0.3 * enter) * (1 + exit * 0.12);
                let tx = 0;
                let ty = -exit * 5;

                if (it.i > 0) {
                    if (it.mode === 'circle') {
                        ty += (1 - enter) * 8;
                    } else {
                        tx = (1 - enter) * 10;
                    }
                }

                it.image.style.transform =
                    'translate3d(' + tx.toFixed(2) + '%,' + ty.toFixed(2) + '%,0) scale(' + scale.toFixed(4) + ')';
            }

            // --- Dim the slide while the next one covers it ---
            if (it.dim) {
                it.dim.style.opacity = (exit * 0.75).toFixed(3);
            }

            // --- Ghost number parallax ---
            if (it.ghost) {
                it.ghost.style.transform = 'translate3d(' + (-d * 25).toFixed(2) + 'vw,0,0)';
                it.ghost.style.opacity = ((1 - Math.abs(d)) * (it.i === 0 ? intro : 1)).toFixed(3);
            }

            // --- Content leaves upwards while the next slide arrives ---
            if (it.content) {
                it.content.style.opacity = (1 - clamp(exit * 1.6)).toFixed(3);
                it.content.style.transform = 'translate3d(0,' + (-exit * 50).toFixed(1) + 'px,0)';
            }

            // --- Title: word by word ---
            const wl = it.words.length;
            const step = 0.3 / Math.max(wl, 1);
            it.words.forEach(function (w, k) {
                const local = clamp((enter - 0.4 - k * step) / 0.3);
                w.style.transform = 'translate3d(0,' + ((1 - easeOut(local)) * 110).toFixed(2) + '%,0)';
            });

            // --- Other details: staggered fade up ---
            it.reveals.forEach(function (r) {
                const idx = parseInt(r.getAttribute('data-reveal'), 10) || 0;
                const local = clamp((enter - 0.5 - idx * 0.05) / 0.25);
                r.style.opacity = local.toFixed(3);
                r.style.transform = 'translate3d(0,' + ((1 - easeOut(local)) * 30).toFixed(1) + 'px,0)';
            });
        });

        // --- HUD ---
        const idx = clamp(Math.round(pos), 0, S - 1);

        if (bar) bar.style.transform = 'scaleX(' + (pos / (S - 1)).toFixed(4) + ')';

        dots.forEach(function (dot, k) {
            dot.classList.toggle('is-active', k === idx);
        });

        if (counter) {
            const onSculpture = idx < S - 1;
            counter.style.opacity = onSculpture ? '1' : '0';
            if (onSculpture && currentLabel) currentLabel.textContent = pad(idx + 1);
        }
    }

    function tick(now) {
        rafId = null;

        if (introT < 1) {
            if (introStart === null) introStart = now;
            introT = clamp((now - introStart) / 1400);
        }

        const k = reduceMotion ? 1 : 0.1;
        current += (target - current) * k;
        if (Math.abs(target - current) < 0.0004) current = target;

        render(current);

        if (current !== target || introT < 1) {
            rafId = requestAnimationFrame(tick);
        }
    }

    function request() {
        if (rafId === null) rafId = requestAnimationFrame(tick);
    }

    function onScroll() {
        readTarget();
        request();
    }

    // Click on a dot -> scroll to that slide
    dots.forEach(function (dot, k) {
        dot.addEventListener('click', function () {
            const total = root.offsetHeight - window.innerHeight;
            const top = root.getBoundingClientRect().top + window.scrollY;
            window.scrollTo({
                top: top + (k / (S - 1)) * total,
                behavior: reduceMotion ? 'auto' : 'smooth'
            });
        });
    });

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);

    // Initial state
    readTarget();
    current = target;

    // Play the intro only if the user is at the very beginning of the section
    if (target > 0.01 || root.getBoundingClientRect().top < -50) introT = 1;

    render(current);
    request();
}

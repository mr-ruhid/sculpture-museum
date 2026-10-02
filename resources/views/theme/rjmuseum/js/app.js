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
    const easeOutExpo = function (t) {
        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
    };
    const pad = function (n) { return n < 10 ? '0' + n : String(n); };

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

    let target = 0;
    let current = 0;
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

        target = base + easeInOut(clamp((frac - 0.1) / 0.8));
    }

    function render(pos) {
        const intro = easeOutExpo(introT);

        items.forEach(function (it) {
            const d = pos - it.i;

            if (d <= -1.05 || d >= 1.05) {
                it.el.style.visibility = 'hidden';
                it.el.style.pointerEvents = 'none';
                return;
            }

            it.el.style.visibility = 'visible';
            it.el.style.pointerEvents = Math.abs(d) < 0.5 ? 'auto' : 'none';

            const enter = it.i === 0 ? intro : clamp(1 + d);
            const exit = clamp(d);

            // --- Clip-path reveal (giriş) ---
            let clip = 'none';
            if (it.i > 0 && enter < 1) {
                if (it.mode === 'circle') {
                    const r = easeOut(enter) * 150;
                    clip = 'circle(' + r.toFixed(2) + '% at 50% 100%)';
                } else {
                    const off = (1 - easeOut(enter)) * 100;
                    const rad = ((1 - enter) * 80).toFixed(1);
                    clip = 'inset(0 0 0 ' + off.toFixed(2) + '% round ' + rad + 'px 0 0 ' + rad + 'px)';
                }
            }
            it.el.style.clipPath = clip;
            it.el.style.webkitClipPath = clip;

            // --- Şəkil: zoom + parallax + blur ---
            if (it.image) {
                const scale = (1.25 - 0.25 * enter) * (1 + exit * 0.15);
                let tx = 0;
                let ty = -exit * 6;

                if (it.i > 0) {
                    if (it.mode === 'circle') {
                        ty += (1 - enter) * 10;
                    } else {
                        tx = (1 - enter) * 12;
                    }
                }

                // Çıxışda blur + tündləşmə
                const blurAmt = exit * 14 + (it.i > 0 ? (1 - enter) * 6 : 0);
                const bright = 1 - exit * 0.45 - (it.i > 0 ? (1 - enter) * 0.25 : 0);
                const sat = 1 - exit * 0.3;

                it.image.style.transform =
                    'translate3d(' + tx.toFixed(2) + '%,' + ty.toFixed(2) + '%,0) scale(' + scale.toFixed(4) + ')';
                it.image.style.filter =
                    'blur(' + blurAmt.toFixed(2) + 'px) brightness(' + bright.toFixed(3) + ') saturate(' + sat.toFixed(3) + ')';
            }

            // --- Dim qatı ---
            if (it.dim) {
                it.dim.style.opacity = (exit * 0.7).toFixed(3);
            }

            // --- Ghost nömrə ---
            if (it.ghost) {
                it.ghost.style.transform = 'translate3d(' + (-d * 30).toFixed(2) + 'vw,0,0)';
                it.ghost.style.opacity = ((1 - Math.abs(d)) * (it.i === 0 ? intro : 1) * 0.9).toFixed(3);
            }

            // --- Məzmun çıxış ---
            if (it.content) {
                const co = 1 - clamp(exit * 1.8);
                it.content.style.opacity = co.toFixed(3);
                it.content.style.transform = 'translate3d(0,' + (-exit * 80).toFixed(1) + 'px,0)';
                it.content.style.filter = 'blur(' + (exit * 6).toFixed(2) + 'px)';
            }

            // --- Başlıq: söz-söz ---
            const wl = it.words.length;
            const step = 0.25 / Math.max(wl, 1);
            it.words.forEach(function (w, k) {
                const local = clamp((enter - 0.35 - k * step) / 0.35);
                const e = easeOutExpo(local);
                w.style.transform = 'translate3d(0,' + ((1 - e) * 120).toFixed(2) + '%,0)';
                w.style.opacity = e.toFixed(3);
            });

            // --- Detallar: stagger fade-up ---
            it.reveals.forEach(function (r) {
                const idx = parseInt(r.getAttribute('data-reveal'), 10) || 0;
                const local = clamp((enter - 0.55 - idx * 0.05) / 0.3);
                const e = easeOut(local);
                r.style.opacity = e.toFixed(3);
                r.style.transform = 'translate3d(0,' + ((1 - e) * 35).toFixed(1) + 'px,0)';
                r.style.filter = 'blur(' + ((1 - e) * 4).toFixed(2) + 'px)';
            });
        });

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
            introT = clamp((now - introStart) / 1600);
        }

        const k = reduceMotion ? 1 : 0.085;
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

    readTarget();
    current = target;

    if (target > 0.01 || root.getBoundingClientRect().top < -50) introT = 1;

    render(current);
    request();
}

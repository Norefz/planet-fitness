{{--
    Partial: resources/views/partials/global-styles.blade.php
    Utility CSS bersama untuk seluruh panel (Member, Mentor, Admin).
    v2 — menambahkan bahasa desain "Orbit": mesh gradient, tilt 3D,
    tombol magnetic, dan noise texture ala situs premium (referensi Apple.com).
--}}
<style>
    html { scroll-behavior: smooth; }

    /* ── Accessibility: focus-visible ring konsisten di semua elemen interaktif ── */
    a:focus-visible, button:focus-visible, input:focus-visible,
    textarea:focus-visible, select:focus-visible, [tabindex]:focus-visible {
        outline: 2px solid #1d9e75;
        outline-offset: 2px;
        border-radius: 4px;
    }

    /* ── Custom scrollbar (konsisten dgn scrollbar-thin milik mentor) ── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* ── Glassmorphism helper ── */
    .glass {
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }
    .glass-light {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    /* ── Hover-lift micro-interaction (dipakai di kartu2 seluruh app) ── */
    .hover-lift { transition: transform .25s cubic-bezier(.16,1,.3,1), box-shadow .25s ease; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -10px rgb(15 23 42 / .18); }

    /* ── Gradient text ── */
    .text-gradient {
        background: linear-gradient(120deg, #4ade9e, #1d9e75 60%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .text-gradient-light {
        background: linear-gradient(120deg, #ffffff, #a7ebd4 65%, #4ade9e);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    /* ── Skeleton loading shimmer ── */
    .skeleton {
        position: relative;
        overflow: hidden;
        background: #eef2f2;
        border-radius: 12px;
    }
    .skeleton::after {
        content: '';
        position: absolute; inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.85), transparent);
        animation: pf-shimmer 1.6s infinite;
    }
    @keyframes pf-shimmer { 100% { transform: translateX(100%); } }

    /* ── Scroll-reveal (dipasangkan dengan IntersectionObserver di app-scripts) ── */
    .reveal-on-scroll {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity .6s cubic-bezier(.16,1,.3,1), transform .6s cubic-bezier(.16,1,.3,1);
    }
    .reveal-on-scroll.is-visible { opacity: 1; transform: translateY(0); }

    /* ── Form validation state ── */
    .field-invalid {
        border-color: #fca5a5 !important;
        background-color: #fff5f5;
    }
    .field-invalid:focus, .field-invalid:focus-visible {
        outline-color: #ef4444;
        box-shadow: 0 0 0 3px rgb(239 68 68 / .15);
    }
    @keyframes pf-shake {
        10%, 90% { transform: translateX(-1px); }
        20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-4px); }
        40%, 60% { transform: translateX(4px); }
    }
    .field-shake { animation: pf-shake .5s cubic-bezier(.36,.07,.19,.97) both; }

    /* ═══════════════════════════════════════════════════════════════════
       "Orbit" design language — referensi Apple.com: panel gelap, orb 3D
       yang mengambang, tilt kartu, dan tombol magnetic.
       ═══════════════════════════════════════════════════════════════════ */

    /* Mesh gradient background untuk panel gelap (hero, sidebar auth, dst) */
    .mesh-dark {
        position: relative;
        background:
            radial-gradient(60% 50% at 15% 20%, rgba(29,158,117,0.55), transparent 60%),
            radial-gradient(50% 45% at 85% 15%, rgba(58,196,159,0.35), transparent 60%),
            radial-gradient(70% 60% at 50% 100%, rgba(10,38,64,0.9), transparent 60%),
            linear-gradient(160deg, #060b09 0%, #0a1a14 45%, #081310 100%);
    }

    /* Orb 3D mengambang — dipakai sebagai elemen dekoratif absolute */
    .orb {
        border-radius: 9999px;
        filter: blur(2px);
        background: radial-gradient(circle at 32% 28%, rgba(255,255,255,0.55), rgba(74,222,158,0.55) 35%, rgba(15,110,86,0.15) 70%, transparent 78%);
        box-shadow: 0 0 120px 20px rgba(29,158,117,0.35), inset -20px -20px 60px rgba(0,0,0,0.35), inset 12px 12px 40px rgba(255,255,255,0.15);
    }
    .orb-mini {
        border-radius: 9999px;
        background: radial-gradient(circle at 35% 30%, rgba(255,255,255,0.65), rgba(74,222,158,0.5) 40%, transparent 75%);
        box-shadow: 0 0 40px 6px rgba(29,158,117,0.35);
    }

    /* Noise texture halus di atas panel gelap supaya tidak flat */
    .noise-overlay { position: relative; }
    .noise-overlay::before {
        content: '';
        position: absolute; inset: 0;
        pointer-events: none;
        opacity: 0.05;
        mix-blend-mode: overlay;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    }

    /* Kartu tilt 3D — pasangkan dengan data-tilt, JS di bawah menghitung rotateX/Y */
    [data-tilt] {
        transform-style: preserve-3d;
        transition: transform .15s ease-out, box-shadow .3s ease;
        will-change: transform;
    }

    /* Tombol magnetic — sedikit mengikuti kursor, dipasangkan dengan data-magnetic */
    [data-magnetic] { transition: transform .2s cubic-bezier(.16,1,.3,1); }

    /* Spotlight cursor-follow di kartu gelap */
    .spotlight-card { position: relative; overflow: hidden; }
    .spotlight-card::before {
        content: '';
        position: absolute; inset: -1px;
        opacity: 0;
        transition: opacity .4s ease;
        background: radial-gradient(320px circle at var(--x, 50%) var(--y, 50%), rgba(255,255,255,0.10), transparent 60%);
        pointer-events: none;
    }
    .spotlight-card:hover::before { opacity: 1; }

    /* Big Apple-style display heading */
    .display-heading {
        letter-spacing: -0.035em;
        line-height: 1.03;
    }

    /* Button shine sweep — used on primary CTAs */
    .btn-shine::after {
        content: '';
        position: absolute;
        top: 0; left: -60%;
        width: 40%; height: 100%;
        background: linear-gradient(115deg, transparent, rgba(255,255,255,.4), transparent);
        transform: skewX(-20deg);
        transition: left .65s cubic-bezier(.16,1,.3,1);
        pointer-events: none;
    }
    .btn-shine:hover::after { left: 130%; }

    /* Subtle light mesh — decorative backdrop for content pages (not hero panels) */
    .mesh-light {
        background:
            radial-gradient(50% 40% at 8% 0%, rgba(29,158,117,0.06), transparent 60%),
            radial-gradient(40% 35% at 100% 0%, rgba(58,196,159,0.05), transparent 60%);
    }

    /* Faint dot-grid texture, Apple-keynote style */
    .dot-grid {
        background-image: radial-gradient(rgba(15,23,42,0.06) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    /* Soft inner ring used on premium panels/avatars */
    .ring-glass { box-shadow: inset 0 0 0 1px rgba(255,255,255,0.4); }

    /* Respect reduced-motion preference */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.001ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.001ms !important;
            scroll-behavior: auto !important;
        }
    }

    [x-cloak] { display: none !important; }
</style>

<script>
    // ── Tilt 3D: kartu memiringkan diri mengikuti posisi kursor ──
    document.addEventListener('DOMContentLoaded', () => {
        const isCoarsePointer = window.matchMedia('(pointer: coarse)').matches;
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (isCoarsePointer || reduceMotion) return;

        document.querySelectorAll('[data-tilt]').forEach((card) => {
            const strength = parseFloat(card.dataset.tiltStrength || '10');
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const px = (e.clientX - rect.left) / rect.width - 0.5;
                const py = (e.clientY - rect.top) / rect.height - 0.5;
                card.style.transform = `perspective(900px) rotateX(${(-py * strength).toFixed(2)}deg) rotateY(${(px * strength).toFixed(2)}deg) translateZ(0)`;
                card.style.setProperty('--x', `${(px + 0.5) * 100}%`);
                card.style.setProperty('--y', `${(py + 0.5) * 100}%`);
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(900px) rotateX(0deg) rotateY(0deg) translateZ(0)';
            });
        });

        document.querySelectorAll('[data-magnetic]').forEach((btn) => {
            const strength = parseFloat(btn.dataset.magneticStrength || '18');
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const px = (e.clientX - rect.left) / rect.width - 0.5;
                const py = (e.clientY - rect.top) / rect.height - 0.5;
                btn.style.transform = `translate(${(px * strength).toFixed(2)}px, ${(py * strength).toFixed(2)}px)`;
            });
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translate(0, 0)';
            });
        });
    });
</script>

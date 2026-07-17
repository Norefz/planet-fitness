{{--
    Partial: resources/views/partials/global-styles.blade.php
    Utility CSS bersama untuk seluruh panel (Member, Mentor, Admin).
--}}
<style>
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

    /* ── Respect reduced-motion preference ── */
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

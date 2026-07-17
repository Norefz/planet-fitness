{{--
    Partial: resources/views/partials/design-tokens.blade.php

    Satu sumber kebenaran (single source of truth) untuk seluruh token desain
    Planet Fitness — dipakai bareng oleh layout Member/Guest, Mentor, dan Admin
    supaya warna, radius, shadow, dan font selalu konsisten di semua panel.

    Sebelumnya tiap layout punya konfigurasi Tailwind CDN sendiri-sendiri yang
    saling beda nilai (mis. admin tidak punya skala primary-50..900 padahal
    beberapa komponen memakainya) — file ini menyatukan semuanya.
--}}
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                colors: {
                    primary: {
                        50: '#ecfdf5', 100: '#d1f5e8', 200: '#a7ebd4', 300: '#6ddcbc',
                        400: '#3ac49f', 500: '#1d9e75', DEFAULT: '#1d9e75', 600: '#168262',
                        700: '#0f6e56', dark: '#0f6e56', light: '#e1f5ee', mid: '#d0f0e4',
                        800: '#0b5745', 900: '#084537',
                    },
                    navy: { DEFAULT: '#0a1628', mid: '#112240', soft: '#1a3a5c' },
                },
                borderRadius: {
                    xl:  '0.85rem',
                    '2xl': '1.1rem',
                    xl2: '20px',
                },
                boxShadow: {
                    dropdown: '0 16px 40px -8px rgb(15 23 42 / 0.14), 0 4px 12px -4px rgb(15 23 42 / 0.06)',
                    glow: '0 0 0 4px rgb(29 158 117 / 0.15)',
                },
                keyframes: {
                    'fade-in-up': {
                        '0%':   { opacity: 0, transform: 'translateY(14px)' },
                        '100%': { opacity: 1, transform: 'translateY(0)' },
                    },
                    'gradient-shift': {
                        '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                        '50%':      { transform: 'translate(3%, -4%) scale(1.08)' },
                    },
                    shimmer: {
                        '100%': { transform: 'translateX(100%)' },
                    },
                },
                animation: {
                    'fade-in-up': 'fade-in-up 0.6s cubic-bezier(.16,1,.3,1) both',
                    'gradient-slow': 'gradient-shift 12s ease-in-out infinite',
                    shimmer: 'shimmer 1.6s infinite',
                },
            },
        },
    };
</script>

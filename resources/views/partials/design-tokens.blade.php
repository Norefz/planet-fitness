{{--
    Partial: resources/views/partials/design-tokens.blade.php

    Satu sumber kebenaran (single source of truth) untuk seluruh token desain
    Planet Fitness — dipakai bareng oleh layout Member/Guest, Mentor, dan Admin
    supaya warna, radius, shadow, dan font selalu konsisten di semua panel.

    v2 — "Orbit" design language: tipografi besar & rapat, panel gelap
    near-black ala Apple, radius besar, shadow lembut berlapis, dan
    keyframes untuk orb/blob 3D, spotlight, dan tilt.
--}}
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    display: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                colors: {
                    primary: {
                        50: '#ecfdf5', 100: '#d1f5e8', 200: '#a7ebd4', 300: '#6ddcbc',
                        400: '#3ac49f', 500: '#1d9e75', DEFAULT: '#1d9e75', 600: '#168262',
                        700: '#0f6e56', dark: '#0f6e56', light: '#e1f5ee', mid: '#d0f0e4',
                        800: '#0b5745', 900: '#084537',
                    },
                    navy: { DEFAULT: '#0a1628', mid: '#112240', soft: '#1a3a5c' },
                    ink: {
                        50: '#f4f6f7', 100: '#e7ebed', 200: '#c9d1d6', 300: '#9aa7b0',
                        400: '#6b7a85', 500: '#48555f', 600: '#333e47', 700: '#232b32',
                        800: '#141a1f', 900: '#0b0f12', 950: '#050708', DEFAULT: '#0b0f12',
                    },
                },
                borderRadius: {
                    xl:  '0.85rem',
                    '2xl': '1.1rem',
                    '3xl': '1.75rem',
                    '4xl': '2.25rem',
                    xl2: '20px',
                },
                letterSpacing: {
                    tightest: '-0.045em',
                    tighter2: '-0.035em',
                },
                boxShadow: {
                    dropdown: '0 16px 40px -8px rgb(15 23 42 / 0.14), 0 4px 12px -4px rgb(15 23 42 / 0.06)',
                    glow: '0 0 0 4px rgb(29 158 117 / 0.15)',
                    elevated: '0 30px 60px -15px rgb(2 6 12 / 0.35), 0 10px 24px -10px rgb(2 6 12 / 0.25)',
                    'elevated-lg': '0 50px 100px -20px rgb(2 6 12 / 0.45), 0 15px 40px -15px rgb(2 6 12 / 0.35)',
                    'inner-glow': 'inset 0 1px 0 0 rgb(255 255 255 / 0.08)',
                    'card-3d': '0 2px 6px rgb(15 23 42 / 0.06), 0 20px 40px -14px rgb(15 23 42 / 0.18)',
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
                    'orb-float': {
                        '0%, 100%': { transform: 'translate3d(0, 0, 0) rotate(0deg) scale(1)' },
                        '33%':      { transform: 'translate3d(2%, -5%, 0) rotate(6deg) scale(1.05)' },
                        '66%':      { transform: 'translate3d(-3%, 3%, 0) rotate(-4deg) scale(0.97)' },
                    },
                    'orb-spin': {
                        '0%':   { transform: 'rotate(0deg)' },
                        '100%': { transform: 'rotate(360deg)' },
                    },
                    'float-y': {
                        '0%, 100%': { transform: 'translateY(0px)' },
                        '50%':      { transform: 'translateY(-10px)' },
                    },
                    'pop-in': {
                        '0%':   { opacity: 0, transform: 'translateY(10px) scale(0.98)' },
                        '100%': { opacity: 1, transform: 'translateY(0) scale(1)' },
                    },
                },
                animation: {
                    'fade-in-up': 'fade-in-up 0.6s cubic-bezier(.16,1,.3,1) both',
                    'gradient-slow': 'gradient-shift 12s ease-in-out infinite',
                    shimmer: 'shimmer 1.6s infinite',
                    'orb-float': 'orb-float 16s ease-in-out infinite',
                    'orb-float-slow': 'orb-float 26s ease-in-out infinite',
                    'orb-spin-slow': 'orb-spin 40s linear infinite',
                    'float-y': 'float-y 5s ease-in-out infinite',
                    'pop-in': 'pop-in 0.5s cubic-bezier(.16,1,.3,1) both',
                },
            },
        },
    };
</script>

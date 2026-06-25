<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | Planet Fitness</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: {
            primary: {
              DEFAULT: '#1d9e75',
              dark:    '#0f6e56',
              light:   '#e1f5ee',
            }
          },
          borderRadius: { xl: '1rem', '2xl': '1.25rem' }
        }
      }
    }
  </script>
</head>
<body class="font-sans bg-slate-50 text-slate-900 min-h-screen flex flex-col">

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
      <div class="flex items-start gap-3 bg-primary-light border border-primary text-primary-dark rounded-xl px-4 py-3 shadow-md text-sm font-medium">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="9 12 11 14 15 10"/><circle cx="12" cy="12" r="10"/></svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <div class="flex-1 flex items-center justify-center px-4 py-10">
    @yield('content')
  </div>

</body>
</html>

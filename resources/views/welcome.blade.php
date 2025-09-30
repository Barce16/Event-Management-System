<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Style+Script&family=Dancing+Script:wght@400..700&family=Libre+Caslon+Display&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Niconne&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen text-neutral-900 antialiased selection:bg-black selection:text-white">

    <!-- HEADER CONTAINER (Top Bar + Navbar) -->
    <div id="header-container" class="relative z-50">

        <!-- Top Bar -->
        <div id="top-bar" class="bg-gray-950 text-white text-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-10">
                <!-- Left: Social Media -->
                <div class="flex items-center gap-4">
                    <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                        class="hover:text-gray-400" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank"
                        class="hover:text-gray-400" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                            <path
                                d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                            <circle cx="18.406" cy="5.594" r="1.44" />
                        </svg>
                    </a>
                </div>

                <!-- Right: Email & Phone -->
                <div class="flex items-center gap-6">
                    <span>michaelhoevents@gmail.com</span>
                    <span>+639173062531</span>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <header id="navbar" class="bg-white/80 shadow-sm">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="py-5 flex items-center justify-between">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-16">
                    </a>
                    <div class="flex items-center gap-3 sm:gap-5 text-sm font-medium">
                        <a href="#">Home</a>
                        <a href="{{ route('events.index') }}">Events</a>
                        <a href="{{ Route::has('login') ? route('login') : '#' }}">Log in</a>
                    </div>
                </div>
            </nav>
        </header>
    </div>

    <!-- Spacer for sticky navbar -->
    <div id="navbar-spacer" class="h-0"></div>

    <!-- HERO SECTION -->
    <div class="relative min-h-screen flex flex-col items-center justify-center"
        style="background-image: url('{{ asset('images/hero.jpg') }}'); background-size: cover; background-position: center;">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black opacity-70"></div>

        <div class="relative text-center text-white px-4">
            <h1 class="text-4xl sm:text-7xl font-bold mb-4 font-libre">
                Michael Ho Events Styling & Coordination
            </h1>
            <p class="text-lg sm:text-4xl mb-6 font-style-script">
                Making your special moments unforgettable.
            </p>
            <a href="{{ route('events.index') }}" class="group relative inline-flex items-center bg-white text-black font-medium px-6 py-3 rounded-xl shadow-lg 
          hover:bg-gray-900 hover:text-white hover:pl-10 duration-300 overflow-hidden">
                <svg class="absolute left-3 w-5 h-5 transform -translate-x-10 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
                <span class="transition-transform duration-300">View Events</span>
            </a>

        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-950 text-white py-10">
        <div
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <p class="text-sm">© {{ date('Y') }} Michael Ho Events Styling & Coordination.</p>
            <div class="flex items-center gap-6 text-sm">
                <a href="#" class="hover:underline">Home</a>
                <a href="{{ route('events.index') }}" class="hover:underline">Events</a>
                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="hover:underline">Log in</a>
            </div>
            <div class="flex items-center gap-4 mt-4 sm:mt-0">
                <a href="https://www.facebook.com/MichaelHoEventsPlanningandCoordinating/" target="_blank"
                    class="hover:text-gray-400" aria-label="Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.794.716-1.794 1.764v2.312h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.406 24 22.676V1.325C24 .593 23.406 0 22.675 0z" />
                    </svg>
                </a>
                <a href="https://www.instagram.com/michaelhoevents/?hl=en" target="_blank" class="hover:text-gray-400"
                    aria-label="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.056 1.97.24 2.43.403a4.92 4.92 0 011.675 1.087 4.92 4.92 0 011.087 1.675c.163.46.347 1.26.403 2.43.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.056 1.17-.24 1.97-.403 2.43a4.918 4.918 0 01-1.087 1.675 4.918 4.918 0 01-1.675 1.087c-.46.163-1.26.347-2.43.403-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.056-1.97-.24-2.43-.403a4.918 4.918 0 01-1.675-1.087 4.918 4.918 0 01-1.087-1.675c-.163-.46-.347-1.26-.403-2.43C2.175 15.747 2.163 15.367 2.163 12s.012-3.584.07-4.85c.056-1.17.24-1.97.403-2.43a4.92 4.92 0 011.087-1.675A4.92 4.92 0 015.398 2.636c.46-.163 1.26-.347 2.43-.403C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.013 7.052.072 5.775.131 4.772.348 3.95.692a6.918 6.918 0 00-2.53 1.656A6.918 6.918 0 00.692 4.878c-.344.822-.561 1.825-.62 3.102C.013 8.332 0 8.741 0 12c0 3.259.013 3.668.072 4.948.059 1.277.276 2.28.62 3.102a6.918 6.918 0 001.656 2.53 6.918 6.918 0 002.53 1.656c.822.344 1.825.561 3.102.62C8.332 23.987 8.741 24 12 24s3.668-.013 4.948-.072c1.277-.059 2.28-.276 3.102-.62a6.918 6.918 0 002.53-1.656 6.918 6.918 0 001.656-2.53c.344-.822.561-1.825.62-3.102.059-1.28.072-1.689.072-4.948s-.013-3.668-.072-4.948c-.059-1.277-.276-2.28-.62-3.102a6.918 6.918 0 00-1.656-2.53A6.918 6.918 0 0019.05.692c-.822-.344-1.825-.561-3.102-.62C15.668.013 15.259 0 12 0z" />
                        <path
                            d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a3.999 3.999 0 110-7.998 3.999 3.999 0 010 7.998z" />
                        <circle cx="18.406" cy="5.594" r="1.44" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <script>
        const topBar = document.getElementById('top-bar');
        const navbar = document.getElementById('navbar');
        const spacer = document.getElementById('navbar-spacer');
        
        const topBarHeight = topBar.offsetHeight;

        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY || window.pageYOffset;

            if (scrollY >= topBarHeight) {
                navbar.classList.add('fixed', 'top-0', 'left-0', 'w-full', 'z-50');
                topBar.classList.add('hidden');
                spacer.style.height = navbar.offsetHeight + 'px';
            } else {
                navbar.classList.remove('fixed', 'top-0', 'left-0', 'w-full', 'z-50');
                topBar.classList.remove('hidden');
                spacer.style.height = '0px';
            }
        });

    </script>

</body>

</html>
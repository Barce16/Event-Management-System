<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        .hero-bg {
            background-image:
                linear-gradient(to bottom, rgba(0, 0, 0, .55), rgba(0, 0, 0, .55)),
                /* swap this later with your own hero image */
                url('https://images.unsplash.com/photo-1617872051806-e9e08b70d3af?q=80&w=1170&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .glass {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, .12);
        }

        .glass-dark {
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, .25);
        }
    </style>
</head>

<body
    class="min-h-screen bg-gray-100 text-neutral-900 antialiased selection:bg-black selection:text-white dark:bg-neutral-900 dark:text-neutral-100">
    <!-- NAVBAR -->
    <header class="fixed top-0 inset-x-0 z-40">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mt-4 rounded-2xl glass-dark text-white border border-white/10 shadow-lg">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-semibold tracking-wide">
                        <span class="text-2xl font-[600]" style="font-family:'Playfair Display',serif;">Michael
                            Ho</span>
                        <span class="hidden sm:inline text-sm opacity-80">Events Styling & Coordination</span>
                    </a>

                    @if(Route::has('login'))
                    <div class="flex items-center gap-2 sm:gap-3">
                        @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center rounded-xl bg-white/15 hover:bg-white/25 px-4 py-2 text-sm font-medium transition">
                            Dashboard
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-xl bg-white/0 hover:bg-white/15 px-4 py-2 text-sm font-medium transition">
                            Log in
                        </a>
                        @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center rounded-xl bg-white text-neutral-900 px-4 py-2 text-sm font-semibold shadow hover:shadow-md transition">
                            Register
                        </a>
                        @endif
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- HERO -->
    <section class="hero-bg relative isolate min-h-[90vh] flex items-center pt-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div class="text-white">
                    <p class="uppercase tracking-[0.25em] text-xs mb-4 text-white/80">Client Portal</p>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">
                        Your event, <span class="text-white/90">organized and effortless.</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-white/80">
                        Sign in to view your event details, timeline, tasks we’re handling for you, payments, and
                        updates—all in one secure place.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center rounded-xl bg-white text-neutral-900 px-5 py-3 text-sm font-semibold shadow hover:shadow-md transition">
                            Go to Dashboard
                        </a>
                        @else
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center rounded-xl bg-white text-neutral-900 px-5 py-3 text-sm font-semibold shadow hover:shadow-md transition">
                            Create your client account
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center rounded-xl bg-white/15 hover:bg-white/25 px-5 py-3 text-sm font-medium transition">
                            I already have an account
                        </a>
                        @endauth
                    </div>
                </div>

                <div class="lg:justify-end flex">
                    <div
                        class="w-full max-w-md lg:max-w-lg glass rounded-2xl border border-white/15 p-6 text-white shadow-xl">
                        <h3 class="text-lg font-semibold">What you can access</h3>
                        <ul class="mt-4 space-y-3 text-sm/6">
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                Event overview & schedule (itinerary/timeline)
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full bg-sky-400"></span>
                                Messages & real-time updates about your event
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full bg-fuchsia-400"></span>
                                Payment records & invoices
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-block h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                                Share feedback after your event
                            </li>
                        </ul>

                        @guest
                        <div class="mt-6">
                            <a href="{{ route('register') }}"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-white text-neutral-900 px-5 py-3 text-sm font-semibold shadow hover:shadow-md transition">
                                Create a free client account
                            </a>
                        </div>
                        @endguest

                        {{-- intentionally no mention of staff/admin --}}
                    </div>
                </div>
            </div>
        </div>

        <div
            class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-neutral-50/90 to-transparent dark:from-neutral-900/90">
        </div>
    </section>

    <!-- FEATURE CARDS (client-facing only) -->
    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="rounded-2xl border bg-white/70 backdrop-blur shadow-sm p-6 dark:bg-neutral-800/60 dark:border-neutral-700">
                    <h4 class="font-semibold">Your Event Hub</h4>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">Everything about your event in one
                        place.</p>
                </div>
                <div
                    class="rounded-2xl border bg-white/70 backdrop-blur shadow-sm p-6 dark:bg-neutral-800/60 dark:border-neutral-700">
                    <h4 class="font-semibold">Timeline & Checklist</h4>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">Know what’s happening and when.</p>
                </div>
                <div
                    class="rounded-2xl border bg-white/70 backdrop-blur shadow-sm p-6 dark:bg-neutral-800/60 dark:border-neutral-700">
                    <h4 class="font-semibold">Secure Payments</h4>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">View payments and downloadable
                        invoices.</p>
                </div>
                <div
                    class="rounded-2xl border bg-white/70 backdrop-blur shadow-sm p-6 dark:bg-neutral-800/60 dark:border-neutral-700">
                    <h4 class="font-semibold">Notifications</h4>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">Receive updates and reminders
                        instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-neutral-200/60 py-10 dark:border-neutral-800/80">
        <div
            class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                © {{ date('Y') }} Michael Ho Events Styling & Coordination.
            </p>
            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="hover:underline">Log in</a>
                <span class="mx-2 opacity-50">•</span>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
                @endif
            </div>
        </div>
    </footer>
</body>

</html>
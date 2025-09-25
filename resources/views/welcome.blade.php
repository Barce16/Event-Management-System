<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|playfair-display:600" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        .hero-bg {
            background-image:
                linear-gradient(to bottom, rgba(0, 0, 0, .55), rgba(0, 0, 0, .55)),

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
                    </div>
                </div>
            </div>
        </div>

        <div
            class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-neutral-50/90 to-transparent dark:from-neutral-900/90">
        </div>
    </section>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Available Packages --}}
            @if(!empty($packages) && $packages->count())
            <div class="bg-white/0 shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-xl mb-4">Available Packages</h3>

                @php
                $pal = [
                ['grad' => 'from-emerald-700 to-emerald-900','ring' => 'ring-emerald-700/40','pill' => 'bg-emerald-800
                text-emerald-100','price' => 'text-emerald-900'],
                ['grad' => 'from-sky-700 to-sky-900','ring' => 'ring-sky-700/40','pill' => 'bg-sky-800
                text-sky-100','price' => 'text-sky-900'],
                ['grad' => 'from-violet-700 to-violet-900','ring' => 'ring-violet-700/40','pill' => 'bg-violet-800
                text-violet-100','price' => 'text-violet-900'],
                ['grad' => 'from-amber-700 to-amber-900','ring' => 'ring-amber-700/40','pill' => 'bg-amber-800
                text-amber-100','price' => 'text-amber-900'],
                ];
                @endphp

                <div class="space-y-6">
                    @foreach($packages as $idx => $p)
                    @php
                    $c = $pal[$idx % count($pal)];
                    $incs = $p->inclusions ?? collect();
                    $sty = is_array($p->event_styling ?? null) ? $p->event_styling : [];
                    $price = $p->price ?? null;

                    // Build 4 gallery images from DB with placeholders fallback
                    $g = [];
                    $dbImgs = $p->images ?? collect();
                    for ($i = 0; $i < 4; $i++) { $img=$dbImgs[$i] ?? null; $g[$i]=[ 'url'=> $img?->url ?:
                        "https://picsum.photos/seed/pkg-{$p->id}-{$i}/960/640",
                        'alt' => $img?->alt ?: "Package image",
                        ];
                        }
                        @endphp

                        <div
                            class="rounded-2xl overflow-hidden bg-white ring-1 {{ $c['ring'] }} shadow-sm hover:shadow-md transition">
                            <div class="p-4 grid gap-4 lg:grid-cols-3 items-stretch">
                                <div class="space-y-4 flex flex-col">
                                    <div class="p-4 bg-gradient-to-br {{ $c['grad'] }} text-white rounded-t-lg">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-xl font-semibold tracking-tight">{{ $p->name }}</div>
                                                @if(!is_null($price))
                                                <div class="mt-0.5 text-lg font-bold">₱{{ number_format($p->price, 2) }}
                                                </div>
                                                @endif
                                            </div>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur">
                                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        @if($p->description)
                                        <p class="mt-2 text-white/90 text-sm leading-snug">{{
                                            Str::limit($p->description, 140) }}</p>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Coordination
                                            </div>
                                            <div class="mt-1 font-semibold {{ $c['price'] }}">₱{{
                                                number_format($p->coordination_price ?? 25000, 2) }}</div>
                                        </div>
                                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Event Styling
                                            </div>
                                            <div class="mt-1 font-semibold {{ $c['price'] }}">₱{{
                                                number_format($p->event_styling_price ?? 55000, 2) }}</div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-xs uppercase tracking-wide text-gray-500 mb-2">Inclusions</div>
                                        @if($incs->isEmpty())
                                        <div class="text-sm text-gray-500">—</div>
                                        @else
                                        <ul class="space-y-2">
                                            @foreach($incs as $inc)
                                            <li class="rounded-lg border border-gray-200 bg-white p-3">
                                                <div class="font-medium text-gray-900">{{ $inc->name }}</div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>

                                    <div class="pt-1 mt-auto">
                                        <a href="{{ route('register') }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 text-white px-5 py-2 text-sm hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                                            Book this package
                                        </a>
                                    </div>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="grid grid-cols-2 gap-2 h-full">
                                        <figure class="col-span-2 aspect-[16/9] rounded-xl overflow-hidden relative">
                                            <img src="{{ $g[0]['url'] }}" alt="{{ $g[0]['alt'] }}"
                                                class="w-full h-full object-cover block" loading="lazy">
                                        </figure>

                                        <figure class="aspect-[4/3] rounded-xl overflow-hidden relative">
                                            <img src="{{ $g[1]['url'] }}" alt="{{ $g[1]['alt'] }}"
                                                class="w-full h-full object-cover block" loading="lazy">
                                        </figure>
                                        <figure class="aspect-[4/3] rounded-xl overflow-hidden relative">
                                            <img src="{{ $g[2]['url'] }}" alt="{{ $g[2]['alt'] }}"
                                                class="w-full h-full object-cover block" loading="lazy">
                                        </figure>

                                        <figure class="col-span-2 aspect-[16/6] rounded-xl overflow-hidden relative">
                                            <img src="{{ $g[3]['url'] }}" alt="{{ $g[3]['alt'] }}"
                                                class="w-full h-full object-cover block" loading="lazy">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

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
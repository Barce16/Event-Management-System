<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Michael Ho Events Styling And Coordination') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Caslon+Display&family=Style+Script&display=swap"
        rel="stylesheet">

    <style>
        .font-libre {
            font-family: 'Libre Caslon Display', serif;
        }

        .font-style-script {
            font-family: 'Style Script', cursive;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-8">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/favicon.png') }}" alt="Logo" class="h-12">
            </a>
            <nav class="flex gap-6 text-sm font-medium">
                <a href="{{ url('/') }}" class="hover:text-gray-700">Home</a>
                <a href="{{ url('/events') }}" class="hover:text-gray-700">Events</a>
                <a href="{{ route('login') }}" class="hover:text-gray-700">Log in</a>
            </nav>
        </div>
    </header>

    <main class="flex flex-col gap-5">
        <div>
            <!-- Page Title -->
            <section class="text-center py-12">
                <h1 class="text-4xl sm:text-5xl font-libre mb-4">Our Wedding Packages</h1>
            </section>

            <!-- Events Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="grid gap-12 grid-cols-2">
                    <!-- Event Card Example -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative">
                            <img src="{{ asset('images/wedding-package-1.jpg') }}" alt="Event Image"
                                class="w-full h-64 object-cover hover:opacity-80 duration-300">
                            <!-- Price Overlay -->
                            <div
                                class="absolute top-4 left-4 bg-white/90 text-black font-semibold px-4 py-2 rounded-lg shadow-md">
                                ₱ 193,000.00
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="py-8 px-0">
                            <h2 class="text-3xl font-libre font-bold mb-6 px-8">Wedding Package 1</h2>


                            <!-- Services Included -->
                            <div class="bg-green-800/50 hover:bg-green-600/50 duration-300 p-6 mb-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coordination -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-emerald-950">Coordination</h4>
                                        <p class="text-emerald-800 text-sm">Planning, organizing and conceptualizing of
                                            event
                                        </p>
                                    </div>

                                    <!-- Event Styling -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-emerald-950">Event Styling</h4>
                                        <div class="space-y-2 text-sm text-emerald-800">
                                            <div>
                                                <p class="font-medium">Ceremony Decoration:</p>
                                                <p class="">Entrance Arch, Aisle, 2-3 Candle Stands</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Reception Decoration:</p>
                                                <p class="">Stage Set-up, Table Centerpieces</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Entourage Bouquets and Corsages</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inclusions Grid -->
                            <div class="space-y-6 px-8">
                                <!-- Invitations -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Invitations
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 sets Digital Printing</li>
                                        <li>• 3 pages: 2 regular sized card, 1 small card</li>
                                        <li>• FREE LAY-OUT</li>
                                    </ul>
                                </div>

                                <!-- Giveaways -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Giveaways
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 pcs.</li>
                                        <li>• With tags/labels</li>
                                        <li>• Choices of: Honey Jars, Coffee Bean Jars, Succulents, Tablea Pouch</li>
                                    </ul>
                                </div>

                                <!-- Photos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Photos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• On-the-Day Coverage</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 50 pcs. 5r Prints</li>
                                        <li>• USB Softcopy of Photos</li>
                                        <li>• 2-4 Photographers</li>
                                    </ul>
                                </div>

                                <!-- Videos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Videos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• Highlights of the Event</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 2-4 Videographers</li>
                                    </ul>
                                </div>

                                <!-- Cake -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Cake
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 3-Tier</li>
                                        <li>• Dimension:</li>
                                        <li>• Choices of Butter and/or Chocolate</li>
                                    </ul>
                                </div>

                                <!-- HMUA -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        HMUA
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• 10 Heads On-the-Day of the Event (including bride)</li>
                                    </ul>
                                </div>

                                <!-- Host -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Host
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• With musical scorer</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="mt-8 flex gap-4 flex-wrap px-8">
                                <a href="#"
                                    class="bg-slate-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-slate-600 transition shadow-md">
                                    BOOK NOW
                                </a>
                                <a href="#"
                                    class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                                    Customize Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Event Card Example -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative">
                            <img src="{{ asset('images/wedding-package-2.jpg') }}" alt="Event Image"
                                class="w-full h-64 object-cover hover:opacity-80 duration-300">
                            <!-- Price Overlay -->
                            <div
                                class="absolute top-4 left-4 bg-white/90 text-black font-semibold px-4 py-2 rounded-lg shadow-md">
                                ₱ 158,000.00
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="py-8 px-0">
                            <h2 class="text-3xl font-libre font-bold mb-6 px-8">Wedding Package 2</h2>


                            <!-- Services Included -->
                            <div class="bg-slate-800/50 hover:bg-slate-600/50 duration-300 p-6 mb-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coordination -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-slate-950">Coordination</h4>
                                        <p class="text-slate-800 text-sm">Planning, organizing and conceptualizing of
                                            event
                                        </p>
                                    </div>

                                    <!-- Event Styling -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-slate-950">Event Styling</h4>
                                        <div class="space-y-2 text-sm text-slate-800">
                                            <div>
                                                <p class="font-medium">Ceremony Decoration:</p>
                                                <p class="">Entrance Arch, Aisle, 2-3 Candle Stands</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Reception Decoration:</p>
                                                <p class="">Stage Set-up, Table Centerpieces</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Entourage Bouquets and Corsages</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inclusions Grid -->
                            <div class="space-y-6 px-8">
                                <!-- Invitations -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Invitations
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 sets Digital Printing</li>
                                        <li>• 3 pages: 2 regular sized card, 1 small card</li>
                                        <li>• FREE LAY-OUT</li>
                                    </ul>
                                </div>

                                <!-- Giveaways -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Giveaways
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 pcs.</li>
                                        <li>• With tags/labels</li>
                                        <li>• Choices of: Honey Jars, Coffee Bean Jars, Succulents, Tablea Pouch</li>
                                    </ul>
                                </div>

                                <!-- Photos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Photos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• On-the-Day Coverage</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 50 pcs. 5r Prints</li>
                                        <li>• USB Softcopy of Photos</li>
                                        <li>• 2-4 Photographers</li>
                                    </ul>
                                </div>

                                <!-- Videos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Videos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• Highlights of the Event</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 2-4 Videographers</li>
                                    </ul>
                                </div>

                                <!-- Cake -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Cake
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 3-Tier</li>
                                        <li>• Dimension:</li>
                                        <li>• Choices of Butter and/or Chocolate</li>
                                    </ul>
                                </div>

                                <!-- HMUA -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        HMUA
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• 10 Heads On-the-Day of the Event (including bride)</li>
                                    </ul>
                                </div>

                                <!-- Host -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Host
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• With musical scorer</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="mt-8 flex gap-4 flex-wrap px-8">
                                <a href="#"
                                    class="bg-slate-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-slate-600 transition shadow-md">
                                    BOOK NOW
                                </a>
                                <a href="#"
                                    class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                                    Customize Package
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
        </div>

        <div>
            <!-- Page Title -->
            <section class="text-center py-12">
                <h1 class="text-4xl sm:text-5xl font-libre mb-4">Our Birthday Packages</h1>
            </section>

            <!-- Events Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <div class="grid gap-12 grid-cols-2">
                    <!-- Event Card Example -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative">
                            <img src="{{ asset('images/debut-package.jpg') }}" alt="Event Image"
                                class="w-full h-64 object-cover hover:opacity-80 duration-300">
                            <!-- Price Overlay -->
                            <div
                                class="absolute top-4 left-4 bg-white/90 text-black font-semibold px-4 py-2 rounded-lg shadow-md">
                                ₱ 162,000.00
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="py-8 px-0">
                            <h2 class="text-3xl font-libre font-bold mb-6 px-8">Debut Package</h2>


                            <!-- Services Included -->
                            <div class="bg-rose-800/50 hover:bg-rose-600/50 duration-300 p-6 mb-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coordination -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-rose-950">Coordination</h4>
                                        <p class="text-rose-800 text-sm">Planning, organizing and conceptualizing of
                                            event
                                        </p>
                                    </div>

                                    <!-- Event Styling -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-rose-950">Event Styling</h4>
                                        <div class="space-y-2 text-sm text-rose-800">
                                            <div>
                                                <p class="font-medium">Ceremony Decoration:</p>
                                                <p class="">Entrance Arch, Aisle, 2-3 Candle Stands</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Reception Decoration:</p>
                                                <p class="">Stage Set-up, Table Centerpieces</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Entourage Bouquets and Corsages</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inclusions Grid -->
                            <div class="space-y-6 px-8">
                                <!-- Invitations -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Invitations
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 sets Digital Printing</li>
                                        <li>• 3 pages: 2 regular sized card, 1 small card</li>
                                        <li>• FREE LAY-OUT</li>
                                    </ul>
                                </div>

                                <!-- Giveaways -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Giveaways
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 pcs.</li>
                                        <li>• With tags/labels</li>
                                        <li>• Choices of: Honey Jars, Coffee Bean Jars, Succulents, Tablea Pouch</li>
                                    </ul>
                                </div>

                                <!-- Photos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Photos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• On-the-Day Coverage</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 50 pcs. 5r Prints</li>
                                        <li>• USB Softcopy of Photos</li>
                                        <li>• 2-4 Photographers</li>
                                    </ul>
                                </div>

                                <!-- Videos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Videos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• Highlights of the Event</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 2-4 Videographers</li>
                                    </ul>
                                </div>

                                <!-- Cake -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Cake
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 3-Tier</li>
                                        <li>• Dimension:</li>
                                        <li>• Choices of Butter and/or Chocolate</li>
                                    </ul>
                                </div>

                                <!-- HMUA -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        HMUA
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• 10 Heads On-the-Day of the Event (including bride)</li>
                                    </ul>
                                </div>

                                <!-- Host -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Host
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• With musical scorer</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="mt-8 flex gap-4 flex-wrap px-8">
                                <a href="#"
                                    class="bg-slate-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-slate-600 transition shadow-md">
                                    BOOK NOW
                                </a>
                                <a href="#"
                                    class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                                    Customize Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Event Card Example -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <!-- Event Image -->
                        <div class="relative">
                            <img src="{{ asset('images/birthday-package.jpg') }}" alt="Event Image"
                                class="w-full h-64 object-cover hover:opacity-80 duration-300">
                            <!-- Price Overlay -->
                            <div
                                class="absolute top-4 left-4 bg-white/90 text-black font-semibold px-4 py-2 rounded-lg shadow-md">
                                ₱ 98,000.00
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="py-8 px-0">
                            <h2 class="text-3xl font-libre font-bold mb-6 px-8">Birthday Package</h2>


                            <!-- Services Included -->
                            <div class="bg-blue-800/50 hover:bg-blue-600/50 duration-300 p-6 mb-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Coordination -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-blue-950">Coordination</h4>
                                        <p class="text-blue-800 text-sm">Planning, organizing and conceptualizing of
                                            event
                                        </p>
                                    </div>

                                    <!-- Event Styling -->
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-blue-950">Event Styling</h4>
                                        <div class="space-y-2 text-sm text-blue-800">
                                            <div>
                                                <p class="font-medium">Ceremony Decoration:</p>
                                                <p class="">Entrance Arch, Aisle, 2-3 Candle Stands</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Reception Decoration:</p>
                                                <p class="">Stage Set-up, Table Centerpieces</p>
                                            </div>
                                            <div>
                                                <p class="font-medium">Entourage Bouquets and Corsages</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inclusions Grid -->
                            <div class="space-y-6 px-8">
                                <!-- Invitations -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Invitations
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 sets Digital Printing</li>
                                        <li>• 3 pages: 2 regular sized card, 1 small card</li>
                                        <li>• FREE LAY-OUT</li>
                                    </ul>
                                </div>

                                <!-- Giveaways -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Giveaways
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 30 pcs.</li>
                                        <li>• With tags/labels</li>
                                        <li>• Choices of: Honey Jars, Coffee Bean Jars, Succulents, Tablea Pouch</li>
                                    </ul>
                                </div>

                                <!-- Photos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Photos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• On-the-Day Coverage</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 50 pcs. 5r Prints</li>
                                        <li>• USB Softcopy of Photos</li>
                                        <li>• 2-4 Photographers</li>
                                    </ul>
                                </div>

                                <!-- Videos -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Videos
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• Highlights of the Event</li>
                                        <li>• AVP: Prenup and SDE</li>
                                        <li>• 2-4 Videographers</li>
                                    </ul>
                                </div>

                                <!-- Cake -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Cake
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• 3-Tier</li>
                                        <li>• Dimension:</li>
                                        <li>• Choices of Butter and/or Chocolate</li>
                                    </ul>
                                </div>

                                <!-- HMUA -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        HMUA
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• Prenuptial/Engagement Shoot</li>
                                        <li>• 10 Heads On-the-Day of the Event (including bride)</li>
                                    </ul>
                                </div>

                                <!-- Host -->
                                <div class="border-l-2 border-slate-500/60 pl-4">
                                    <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                                        Host
                                    </h3>
                                    <ul class="space-y-1 text-gray-700 text-sm ml-6">
                                        <li>• With musical scorer</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="mt-8 flex gap-4 flex-wrap px-8">
                                <a href="#"
                                    class="bg-slate-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-slate-600 transition shadow-md">
                                    BOOK NOW
                                </a>
                                <a href="#"
                                    class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                                    Customize Package
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
        </div>
    </main>




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
</body>

</html>
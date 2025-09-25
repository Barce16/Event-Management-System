<x-app-layout>
    @php
    $isCustomer = auth()->user()->user_type === 'customer';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isCustomer ? __('My Dashboard') : __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(!$isCustomer)
            {{-- ================= ADMIN / STAFF VIEW ================= --}}
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Total Events</div>
                    <div class="text-2xl font-bold">{{ $totalEvents ?? '—' }}</div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Customers</div>
                    <div class="text-2xl font-bold">{{ $totalCustomers ?? '—' }}</div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Payments (This Month)</div>
                    <div class="text-2xl font-bold">
                        @if(isset($paymentsThisMonth))
                        ₱{{ number_format($paymentsThisMonth, 0) }}
                        @else
                        —
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Pending Tasks</div>
                    <div class="text-2xl font-bold">{{ $pendingTasks ?? '—' }}</div>
                </div>
            </div>

            {{-- Optional: show customer-specific cards if those vars exist --}}
            @if(isset($upcoming))
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Upcoming My Events</div>
                    <div class="text-2xl font-bold">{{ $upcoming }}</div>
                </div>
            </div>
            @endif


            {{-- Quick Actions --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="#" class="bg-violet-700 text-white px-4 py-2 rounded">View
                        Payments</a>
                    <a href="{{ route('reports.monthly') }}" class="bg-gray-800 text-white px-4 py-2 rounded">Monthly
                        Report</a>
                </div>
            </div>

            {{-- Recent Events --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Recent Events</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2">Customer</th>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents ?? [] as $e)

                            @php
                            $cust = $e->customer;
                            $custName = $cust?->user?->name
                            ?? $cust?->name
                            ?? $cust?->customer_name
                            ?? 'Unknown';

                            $avatarUrl = $cust?->user?->profile_photo_url
                            ?? 'https://ui-avatars.com/api/?name=' . urlencode($custName) .
                            '&background=E5E7EB&color=374151&size=64';
                            @endphp
                            <tr class="border-t">
                                {{-- Customer --}}
                                <td class="py-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $avatarUrl }}" alt="Avatar"
                                            class="h-8 w-8 rounded-full object-cover">
                                        <span class="font-medium text-gray-900">{{ $custName }}</span>
                                    </div>
                                </td>

                                {{-- Event --}}
                                <td class="py-2">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        {{ $e->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $e->venue ?: '—' }}</div>
                                </td>

                                {{-- Date --}}
                                <td>{{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}</td>

                                {{-- Status --}}
                                <td>
                                    @php
                                    $color = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                    };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No recent events.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            @else
            {{-- ================= CUSTOMER VIEW ================= --}}
            {{-- My Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">My Upcoming Events</div>
                    <div class="text-2xl font-bold">{{ $upcoming ?? 0 }}</div>
                </div>
            </div>

            {{-- Customer Quick Actions --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('customer.events.create') }}" class="bg-sky-900 text-white px-4 py-2 rounded">Book
                        an
                        Event</a>
                    <a href="{{ route('customer.events.index') }}"
                        class="bg-emerald-700 text-white px-4 py-2 rounded">My
                        Events</a>
                    <a href="#" class="bg-violet-700 text-white px-4 py-2 rounded">My
                        Payments</a>
                    <a href="{{ route('profile.edit') }}" class="bg-gray-800 text-white px-4 py-2 rounded">Edit
                        Profile</a>
                </div>
            </div>

            {{-- My Recent Events --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">My Recent Events</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents ?? [] as $e)
                            <tr class="border-t">
                                <td class="py-2">
                                    <a href="{{ route('customer.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        {{ $e->name }}
                                    </a>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}</td>
                                <td>
                                    @php
                                    $color = match(strtolower($e->status)) {
                                    'requested' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'scheduled' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                    };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', strtolower($e->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">No recent events.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- Available Packages (Customer) --}}
            @if(!empty($packages) && $packages->count())
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Available Packages</h3>

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
                                            @php
                                            $incNotes = trim((string)($inc->notes ?? ''));
                                            $noteLines = $incNotes !== '' ? preg_split('/\r\n|\r|\n/', $incNotes) : [];
                                            @endphp
                                            <li class="rounded-lg border border-gray-200 bg-white p-3">
                                                <div class="font-medium text-gray-900">
                                                    {{ $inc->name }}
                                                    @if($inc->category)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] {{ $c['pill'] }} ring-1 ring-black/5">
                                                        {{ $inc->category }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @if(!empty($noteLines))
                                                <ul class="mt-1.5 text-xs text-gray-700 list-disc pl-5 space-y-0.5">
                                                    @foreach($noteLines as $line)
                                                    @if(trim($line) !== '')
                                                    <li>{{ $line }}</li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Event Styling
                                        </div>
                                        <div class="mt-2 rounded-xl border border-gray-200 bg-gray-50 p-3">
                                            @if(empty($sty))
                                            <div class="text-sm text-gray-500">—</div>
                                            @else
                                            <ul class="text-sm text-gray-700 list-disc pl-5 space-y-0.5">
                                                @foreach($sty as $item)
                                                @if(trim($item) !== '')
                                                <li>{{ $item }}</li>
                                                @endif
                                                @endforeach
                                            </ul>
                                            @endif
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Coordination
                                        </div>
                                        <div class="text-sm text-gray-700">{{ Str::limit($p->coordination ?? '—', 120)
                                            }}</div>
                                    </div>

                                    <div class="pt-1 mt-auto">
                                        <a href="{{ route('customer.events.create', ['package_id' => $p->id]) }}"
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

            @endif
        </div>
    </div>
</x-app-layout>
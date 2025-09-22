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
                    <a href="{{ route('payments.index') }}" class="bg-violet-700 text-white px-4 py-2 rounded">View
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
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEvents ?? [] as $e)
                            <tr class="border-t">
                                <td class="py-2">
                                    <a href="{{ route('admin.events.show', $e) }}"
                                        class="text-indigo-600 hover:underline">
                                        {{ $e->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $e->venue ?: '—' }}</div>
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
                                        {{ ucfirst($e->status) }}
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
                    <a href="{{ route('payments.index') }}" class="bg-violet-700 text-white px-4 py-2 rounded">My
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
                                        {{ ucfirst($e->status) }}
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
                ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'chip' => 'bg-emerald-100
                text-emerald-800'],
                ['bg' => 'bg-sky-50', 'border' => 'border-sky-200', 'chip' => 'bg-sky-100 text-sky-800'],
                ['bg' => 'bg-violet-50', 'border' => 'border-violet-200', 'chip' => 'bg-violet-100 text-violet-800'],
                ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'chip' => 'bg-amber-100 text-amber-800'],
                ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($packages as $idx => $p)
                    @php
                    $c = $pal[$idx % count($pal)];
                    $vendors = $p->vendors ?? collect();
                    $incs = $p->inclusions ?? collect();
                    $sty = is_array($p->event_styling ?? null) ? $p->event_styling : [];
                    $price = $p->price ?? $p->price ?? null;
                    @endphp

                    <div class="rounded-lg border {{ $c['border'] }} {{ $c['bg'] }} p-4 flex flex-col">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-2xl font-semibold">{{ $p->name }}</div>
                                @if(!is_null($price))
                                <div class="text-gray-700 font-medium">₱{{ number_format($price, 2) }}</div>
                                @endif
                            </div>
                            <span class="px-2 py-1 font-medium rounded text-sm {{ $c['chip'] }}">
                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        @if($p->description)
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($p->description, 120) }}</p>
                        @endif

                        <div class="mt-2 text-sm text-gray-700">
                            <div>Coordination: ₱{{ number_format($p->coordination_price ?? 25000, 2) }}</div>
                            <div>Event Styling: ₱{{ number_format($p->event_styling_price ?? 55000, 2) }}</div>
                        </div>

                        <div class="mt-3">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Inclusions</div>

                            @if($incs->isEmpty())
                            <div class="text-sm text-gray-500">—</div>
                            @else
                            <ul class="text-sm text-gray-800 space-y-2">
                                @foreach($incs as $inc)
                                <li class="border rounded p-2 bg-white/40">
                                    <div class="font-medium">
                                        {{ $inc->name }}
                                        @if($inc->category)
                                        <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                                        @endif
                                    </div>

                                    @php
                                    $notes = trim((string) ($inc->pivot->notes ?? ''));
                                    @endphp

                                    @if($notes !== '')
                                    <ul class="mt-1 text-xs text-gray-700 leading-tight list-disc pl-5 space-y-0.5">
                                        @foreach(preg_split('/\r\n|\r|\n/', $notes) as $line)
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


                        {{-- Coordination (single line preview) --}}
                        <div class="mt-3">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Coordination</div>
                            <div class="text-sm text-gray-700">{{ Str::limit($p->coordination ?? '—', 110) }}</div>
                        </div>


                        {{-- Event Styling (list ALL items) --}}
                        <div class="mt-3">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Event Styling</div>
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

                        {{-- Vendors / Add-ons --}}
                        {{-- <div class="mt-3">
                            <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Vendors</div>
                            @if($vendors->isEmpty())
                            <div class="text-sm text-gray-500">—</div>
                            @else
                            @php
                            $show = $vendors->take(3);
                            $more = max(0, $vendors->count() - $show->count());
                            @endphp
                            <ul class="text-sm text-gray-700 list-disc pl-5 space-y-0.5">
                                @foreach($show as $v)
                                <li>{{ $v->name }} @if($v->category) <span class="text-gray-500">• {{ $v->category
                                        }}</span> @endif</li>
                                @endforeach
                            </ul>
                            @if($more > 0)
                            <div class="text-xs text-gray-500 mt-1">+ {{ $more }} more</div>
                            @endif
                            @endif
                        </div> --}}


                        <div class="mt-4 flex items-center justify-between">
                            {{-- <span class="text-xs text-gray-500">ID: {{ $p->id }}</span> --}}

                            <a href="{{ route('customer.events.create', ['package_id' => $p->id]) }}"
                                class="bg-emerald-700 text-white px-3 py-2 rounded text-sm">
                                Book this package
                            </a>
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
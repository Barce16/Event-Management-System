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

            @endif
        </div>
    </div>
</x-app-layout>
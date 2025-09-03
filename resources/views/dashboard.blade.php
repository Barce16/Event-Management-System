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
                    <div class="text-2xl font-bold">12</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Customers</div>
                    <div class="text-2xl font-bold">8</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Payments (Aug)</div>
                    <div class="text-2xl font-bold">₱25,000</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Pending Tasks</div>
                    <div class="text-2xl font-bold">4</div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    {{-- <a href="{{ route('customer.events.create') }}"
                        class="bg-sky-900 text-white px-4 py-2 rounded">New
                        Event</a>
                    <a href="{{ route('customers.create') }}" class="bg-emerald-700 text-white px-4 py-2 rounded">Add
                        Customer</a> --}}
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
                            <tr class="border-t">
                                <td class="py-2">Sample Wedding</td>
                                <td>2025-09-10</td>
                                <td><span class="px-2 py-1 bg-yellow-200 rounded">Scheduled</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Birthday Bash</td>
                                <td>2025-09-15</td>
                                <td><span class="px-2 py-1 bg-green-200 rounded">Completed</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Corporate Mixer</td>
                                <td>2025-10-05</td>
                                <td><span class="px-2 py-1 bg-blue-200 rounded">Planning</span></td>
                            </tr>
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
                    <div class="text-2xl font-bold">2</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Payments This Month</div>
                    <div class="text-2xl font-bold">₱5,000</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <div class="text-gray-600 text-sm">Open Tasks / Requests</div>
                    <div class="text-2xl font-bold">1</div>
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
                            <tr class="border-t">
                                <td class="py-2">My Wedding</td>
                                <td>2025-09-18</td>
                                <td><span class="px-2 py-1 bg-yellow-200 rounded">Scheduled</span></td>
                            </tr>
                            <tr class="border-t">
                                <td class="py-2">Graduation Party</td>
                                <td>2025-10-02</td>
                                <td><span class="px-2 py-1 bg-blue-200 rounded">Planning</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
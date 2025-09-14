<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Reports</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-4 md:grid-cols-2">

            {{-- Events --}}
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-3">
                <h3 class="font-semibold">Events</h3>
                <div class="grid gap-2">
                    <a class="underline" href="{{ route('admin.reports.events.byMonth') }}">Events by Month</a>
                    <a class="underline" href="{{ route('admin.reports.events.byStatus') }}">Events by Status</a>
                    <a class="underline" href="{{ route('admin.reports.events.upcoming') }}">Upcoming (next 30 days)</a>
                </div>
            </div>

            {{-- Customers --}}
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-3">
                <h3 class="font-semibold">Customers</h3>
                <div class="grid gap-2">
                    <a class="underline" href="{{ route('admin.reports.customers.byMonth') }}">Customers by
                        Month</a>
                    <a class="underline" href="{{ route('admin.reports.customers.top') }}">Top Customers</a>
                </div>
            </div>

            {{-- Vendors & Packages --}}
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-3">
                <h3 class="font-semibold">Vendors & Packages</h3>
                <div class="grid gap-2">
                    <a class="underline" href="{{ route('admin.reports.vendors.top') }}">Top Vendors</a>
                    <a class="underline" href="{{ route('admin.reports.packages.usage') }}">Package Usage</a>
                </div>
            </div>

            {{-- Staff --}}
            <div class="bg-white p-6 rounded-lg shadow-sm space-y-3">
                <h3 class="font-semibold">Staff</h3>
                <div class="grid gap-2">
                    <a class="underline" href="{{ route('admin.reports.staff.workload') }}">Staff Workload</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
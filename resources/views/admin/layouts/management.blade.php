<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Sidebar -->
            <aside class="md:col-span-1 bg-white rounded-lg shadow p-4 space-y-2">
                <a href="{{ route('admin.management.index') }}"
                    class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.index') ? 'bg-gray-100 font-semibold' : '' }}">
                    Overview
                </a>
                <a href="{{ route('admin.management.event-types.index') }}"
                    class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.event-types.*') ? 'bg-gray-100 font-semibold' : '' }}">
                    Event Types
                </a>
                <a href="{{ route('admin.management.services.index') }}"
                    class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.services.*') ? 'bg-gray-100 font-semibold' : '' }}">
                    Services
                </a>
            </aside>

            <!-- Main content -->
            <main class="md:col-span-4">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>
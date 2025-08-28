<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <aside class="bg-white shadow-sm rounded-lg p-4">
                <nav class="space-y-1">
                    <a href="{{ route('admin.management.index') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.index') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                        Overview
                    </a>
                    <a href="{{ route('admin.management.event-types.index') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.event-types.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                        Event Types
                    </a>
                    <a href="{{ route('admin.management.services.index') }}"
                        class="block px-3 py-2 rounded {{ request()->routeIs('admin.management.services.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }}">
                        Services
                    </a>
                </nav>
            </aside>

            {{-- Main content --}}
            <main class="md:col-span-3">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
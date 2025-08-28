<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Vertical Nav --}}
                <aside class="md:col-span-1 bg-white shadow-sm rounded-lg p-4">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.management.index') }}"
                            class="{{ request()->routeIs('admin.management.index') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }} block px-3 py-2 rounded">
                            Overview
                        </a>
                        <a href="{{ route('admin.management.event-types.index') }}"
                            class="{{ request()->routeIs('admin.management.event-types.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }} block px-3 py-2 rounded">
                            Event Types
                        </a>

                        <a href="{{ route('admin.management.services.index') }}"
                            class="{{ request()->routeIs('admin.management.services.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100' }} block px-3 py-2 rounded">
                            Services
                        </a>
                    </nav>
                </aside>

                {{-- Content area --}}
                <section class="md:col-span-3 bg-white shadow-sm rounded-lg p-6">

                    <div class="mt-6 grid sm:grid-cols-2 gap-4">
                        <a href="{{ route('admin.management.event-types.index') }}"
                            class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="font-medium">Manage Event Types</div>
                            <div class="text-sm text-gray-600">Add, edit, or archive common event categories.</div>
                        </a>

                        <a href="{{ route('admin.management.services.index') }}"
                            class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="font-medium">Manage Services</div>
                            <div class="text-sm text-gray-600">Configure add-on services like catering, décor, etc.
                            </div>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
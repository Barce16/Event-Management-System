<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">My Events</h2>
            <a href="{{ route('customer.events.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded">Request
                Event</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Type</th>
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $e)
                        <tr class="border-t">
                            <td class="py-2">{{ $e->name }}</td>
                            <td class="py-2">{{ $e->eventType?->name }}</td>
                            <td class="py-2">{{ $e->event_date?->format('M d, Y') }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 rounded bg-gray-100 capitalize">{{ $e->status }}</span>
                            </td>
                            <td class="py-2">
                                <a href="{{ route('customer.events.show', $e) }}"
                                    class="bg-gray-800 text-white px-3 py-1.5 rounded text-xs">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-6" colspan="5">No events yet. Click “Request Event”.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
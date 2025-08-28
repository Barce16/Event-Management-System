<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Event Types</h3>
            <a href="{{ route('admin.management.event-types.create') }}"
                class="bg-gray-800 text-white px-4 py-2 rounded">New Event Type</a>
        </div>

        {{-- List --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="GET" class="mb-4">
                <input type="text" name="q" value="{{ $q }}" placeholder="Search event types"
                    class="border rounded px-3 py-2 w-full md:w-96">
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Name</th>
                            <th class="text-left py-2">Description</th>
                            <th class="text-left py-2">Status</th>
                            <th class="text-left py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $t)
                        <tr class="border-t">
                            <td class="py-2 font-medium">{{ $t->name }}</td>
                            <td class="py-2 text-gray-600">{{ $t->description ?? '—' }}</td>
                            <td class="py-2">
                                <span
                                    class="px-2 py-1 rounded {{ $t->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $t->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-2 space-x-2">
                                <a href="{{ route('admin.management.event-types.edit',$t) }}" class="underline">Edit</a>
                                <form action="{{ route('admin.management.event-types.toggle',$t) }}" method="POST"
                                    class="inline">
                                    @csrf @method('PATCH')
                                    <button class="underline">{{ $t->is_active ? 'Disable' : 'Enable' }}</button>
                                </form>
                                <form action="{{ route('admin.management.event-types.destroy',$t) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Delete this event type?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="py-4" colspan="4">No event types yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $types->links() }}</div>
        </div>
    </div>
</x-admin.layouts.management>
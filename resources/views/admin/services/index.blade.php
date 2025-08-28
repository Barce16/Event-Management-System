<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Services</h3>
            <a href="{{ route('admin.management.services.create') }}"
                class="bg-gray-800 text-white px-4 py-2 rounded">New Service</a>
        </div>

        <div class="overflow-x-auto bg-white border rounded">
            <table class="min-w-full text-sm">
                <thead class="text-gray-600">
                    <tr>
                        <th class="text-left py-2 px-3">Service</th>
                        <th class="text-left py-2 px-3">Description</th>
                        <th class="text-left py-2 px-3">Base Price</th>
                        <th class="text-left py-2 px-3">Active</th>
                        <th class="text-left py-2 px-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $s)
                    <tr class="border-t">
                        <td class="py-2 px-3 font-medium">{{ $s->name }}</td>
                        <td class="py-2 px-3 text-gray-600">{{ \Illuminate\Support\Str::limit($s->description, 60) }}
                        </td>
                        <td class="py-2 px-3">₱{{ number_format($s->base_price, 2) }}</td>
                        <td class="py-2 px-3">
                            <span
                                class="px-2 py-1 rounded {{ $s->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $s->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="py-2 px-3 space-x-2">
                            <a href="{{ route('admin.management.services.edit', $s) }}" class="underline">Edit</a>
                            <form action="{{ route('admin.management.services.destroy', $s) }}" method="POST"
                                class="inline" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Delete</button>
                            </form>
                            <form action="{{ route('admin.management.services.toggle', $s) }}" method="POST"
                                class="inline">
                                @csrf @method('PATCH')
                                <button class="underline">
                                    {{ $s->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-4 px-3" colspan="5">No services yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $services->links() }}</div>
    </div>
</x-admin.layouts.management>
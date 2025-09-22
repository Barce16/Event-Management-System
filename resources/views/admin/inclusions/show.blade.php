<x-admin.layouts.management>
    <div class="bg-white rounded shadow p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">{{ $inclusion->name }}</h3>
            <a href="{{ route('admin.management.inclusions.edit',$inclusion) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600 text-sm">Contact Person</div>
                <div class="font-medium">{{ $inclusion->contact_person ?: '—' }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Email</div>
                <div class="font-medium">{{ $inclusion->contact_email ?: '—' }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Phone</div>
                <div class="font-medium">{{ $inclusion->contact_phone ?: '—' }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Category</div>
                <div class="font-medium">{{ $inclusion->category ?: '—' }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Price</div>
                <div class="font-medium">₱{{ number_format($inclusion->price,2) }}</div>
            </div>
            <div>
                <div class="text-gray-600 text-sm">Status</div>
                <span
                    class="px-2 py-1 rounded text-xs {{ $inclusion->is_active ? 'bg-green-100 text-green-800':'bg-gray-100 text-gray-800' }}">
                    {{ $inclusion->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="pt-4 border-t">
            <a href="{{ route('admin.management.inclusions.index') }}" class="underline">Back to inclusions</a>
        </div>
    </div>
</x-admin.layouts.management>
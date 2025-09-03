<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">{{ $vendor->name }}</h3>
            <a href="{{ route('admin.management.vendors.edit', $vendor) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-gray-600 text-sm">Price</div>
                <div class="font-medium">₱{{ number_format($vendor->price, 2) }}</div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Status</div>
                <div>
                    @php
                    $badge = $vendor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                        {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Email</div>
                <div class="font-medium">{{ $vendor->email ?: '—' }}</div>
            </div>

            <div>
                <div class="text-gray-600 text-sm">Phone</div>
                <div class="font-medium">{{ $vendor->phone ?: '—' }}</div>
            </div>

            <div class="md:col-span-2">
                <div class="text-gray-600 text-sm">Address</div>
                <div class="font-medium">{{ $vendor->address ?: '—' }}</div>
            </div>

            <div class="md:col-span-2">
                <div class="text-gray-600 text-sm">Notes</div>
                <div class="font-medium whitespace-pre-line">{{ $vendor->notes ?: '—' }}</div>
            </div>
        </div>

        <div class="pt-4 border-t">
            <a href="{{ route('admin.management.vendors.index') }}" class="underline">Back to vendors</a>
        </div>
    </div>
</x-admin.layouts.management>
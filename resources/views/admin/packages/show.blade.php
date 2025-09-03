<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">{{ $package->name }}</h3>
                <div class="text-gray-500">₱{{ number_format($package->price, 2) }}</div>
            </div>
            <a href="{{ route('admin.management.packages.edit', $package) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">
                Edit
            </a>
        </div>

        <div>
            <div class="text-gray-600 text-sm mb-1">Description</div>
            <div class="whitespace-pre-line">{{ $package->description ?: '—' }}</div>
        </div>

        <div>
            <div class="text-gray-600 text-sm mb-1">Status</div>
            @php $badge = $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; @endphp
            <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                {{ $package->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div>
            <div class="text-gray-600 text-sm mb-1">Vendors Included</div>
            @if($package->vendors->isEmpty())
            <div class="text-gray-500">No vendors added.</div>
            @else
            <ul class="list-disc pl-5 space-y-1">
                @foreach($package->vendors as $v)
                <li>
                    <div class="font-medium">{{ $v->name }}</div>
                    <div class="text-gray-500 text-xs">
                        {{ $v->email ?: '' }} {{ $v->phone ? '• '.$v->phone : '' }}
                        @if(!is_null($v->price)) • Default: ₱{{ number_format($v->price, 2) }} @endif
                        @if(!is_null($v->pivot->price_override)) • Override: ₱{{
                        number_format($v->pivot->price_override, 2) }} @endif
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="pt-4 border-t">
            <a href="{{ route('admin.management.packages.index') }}" class="underline">Back to packages</a>
        </div>
    </div>
</x-admin.layouts.management>
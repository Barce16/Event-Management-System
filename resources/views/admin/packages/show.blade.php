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

        <div class="pt-6 border-t">
            <h4 class="text-md font-semibold mb-3">Events</h4>

            @if($eventsUsingPackage->count() === 0)
            <div class="text-sm text-gray-600">No Events</div>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600 border-b">
                            <th class="py-2 pr-4">Date</th>
                            <th class="py-2 pr-4">Event</th>
                            <th class="py-2 pr-4">Customer</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eventsUsingPackage as $e)
                        <tr class="border-b">
                            <td class="py-2 pr-4">
                                {{ \Illuminate\Support\Carbon::parse($e->event_date)->format('Y-m-d') }}
                            </td>
                            <td class="py-2 pr-4">
                                <div class="font-medium">{{ $e->name }}</div>
                                <div class="text-gray-500">{{ $e->venue ?: '—' }}</div>
                            </td>
                            <td class="py-2 pr-4">
                                {{ $e->customer?->customer_name ?? '—' }}
                                <div class="text-gray-500 text-xs">{{ $e->customer?->email ?? '' }}</div>
                            </td>
                            <td class="py-2 pr-4">
                                @php
                                $color = match($e->status){
                                'requested' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-blue-100 text-blue-800',
                                'scheduled' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                                };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $color }}">
                                    {{ ucfirst($e->status) }}
                                </span>
                            </td>
                            <td class="py-2 pr-4">
                                <a href="{{ route('admin.events.show', $e) }}"
                                    class="text-indigo-600 hover:underline">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $eventsUsingPackage->withQueryString()->links() }}
            </div>
            @endif
        </div>

    </div>
</x-admin.layouts.management>
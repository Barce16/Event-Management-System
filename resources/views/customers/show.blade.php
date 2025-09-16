<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Customer: {{ $customer->customer_name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-2">Info</h3>
                <img src="{{ $customer->user->profile_photo_url }}" class="h-16 w-16 rounded-full object-cover mb-2"
                    alt="Avatar">
                <dl class="text-sm grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div><span class="font-medium">Email:</span> {{ $customer->email }}</div>
                    <div><span class="font-medium">Phone:</span> {{ $customer->phone ?? '—' }}</div>
                    <div class="md:col-span-2"><span class="font-medium">Address:</span> {{ $customer->address ?? '—' }}
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-3">Events</h3>
                @if($customer->events->isEmpty())
                <p class="text-gray-600">No events yet.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-gray-600">
                            <tr>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Date</th>
                                <th class="text-left py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->events as $e)
                            <tr class="border-t">
                                <td class="py-2">{{ $e->event_name }}</td>
                                <td class="py-2">{{ optional($e->date)->format('M d, Y') }}</td>
                                <td class="py-2"><span class="px-2 py-1 bg-gray-200 rounded">{{ ucfirst($e->status ??
                                        '—') }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div>
                <a href="{{ route('customers.index') }}" class="inline-block border px-4 py-2 rounded">Back to list</a>
            </div>

        </div>
    </div>
</x-app-layout>
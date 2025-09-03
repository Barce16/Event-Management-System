<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $event->name }}</h2>
            <a href="{{ route('customer.events.index') }}" class="underline">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-600">Date</dt>
                        <dd class="font-medium">{{ $event->event_date?->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Venue</dt>
                        <dd class="font-medium">{{ $event->venue ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Theme</dt>
                        <dd class="font-medium">{{ $event->theme ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Budget</dt>
                        <dd class="font-medium">{{ is_null($event->budget) ? '—' : '₱'.number_format($event->budget,2)
                            }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Guests</dt>
                        <dd class="font-medium">{{ $event->guest_count ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Status</dt>
                        <dd><span class="px-2 py-1 bg-gray-100 rounded capitalize">{{ $event->status }}</span></dd>
                    </div>
                </dl>
                <div class="mt-4">
                    <div class="text-gray-600 text-sm">Notes</div>
                    <div>{{ $event->notes ?? '—' }}</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-2">Selected Services</h3>
                @if ($event->services->isEmpty())
                <p class="text-sm text-gray-600">No services selected.</p>
                @else
                <table class="min-w-full text-sm">
                    <thead class="text-gray-600">
                        <tr>
                            <th class="text-left py-2">Service</th>
                            <th class="text-left py-2">Price </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event->services as $s)
                        <tr class="border-t">
                            <td class="py-2">{{ $s->name }}</td>
                            <td class="py-2">₱{{ number_format($s->pivot->price ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
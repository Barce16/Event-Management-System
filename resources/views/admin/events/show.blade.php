<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Event Details</h2>
            <a href="{{ route('admin.events.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xl font-semibold">{{ $event->name }}</div>
                        <div class="text-gray-600">{{ $event->eventType?->name ?? '—' }}</div>
                    </div>
                    <form action="{{ route('admin.events.status', $event) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="border rounded px-3 py-2" onchange="this.form.submit()">
                            @foreach(['requested','approved','scheduled','completed','cancelled'] as $s)
                            <option value="{{ $s }}" @selected($event->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mt-4 text-sm">
                    <div>
                        <div class="text-gray-500">Date</div>
                        <div>{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Venue</div>
                        <div>{{ $event->venue ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Theme</div>
                        <div>{{ $event->theme ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Budget</div>
                        <div>{{ is_null($event->budget) ? '—' : '₱'.number_format($event->budget,0) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Guests</div>
                        <div>{{ $event->guest_count ?: '—' }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Customer</div>
                    <div class="p-4 rounded border">
                        <div class="font-medium">{{ $event->customer?->customer_name ?? '—' }}</div>
                        <div class="text-gray-600">{{ $event->customer?->email ?? '' }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Selected Services</div>
                    @if($event->services->isEmpty())
                    <div class="text-gray-500">No add-ons.</div>
                    @else
                    <ul class="list-disc pl-5">
                        @foreach($event->services as $s)
                        <li>{{ $s->name }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Notes</div>
                    <div class="whitespace-pre-wrap">{{ $event->notes ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
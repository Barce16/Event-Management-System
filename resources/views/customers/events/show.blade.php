<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $event->name }}</h2>
            <a href="{{ route('customer.events.edit', $event) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-gray-600 text-sm">Date</div>
                        <div class="font-medium">{{
                            \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d') }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Status</div>
                        <div><span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{
                                ucfirst($event->status) }}</span></div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Package</div>
                        <div class="font-medium">{{ $event->package?->name ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Venue</div>
                        <div class="font-medium">{{ $event->venue ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Theme</div>
                        <div class="font-medium">{{ $event->theme ?: '—' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-gray-600 text-sm">Notes</div>
                        <div class="font-medium whitespace-pre-line">{{ $event->notes ?: '—' }}</div>
                    </div>
                </div>

                {{-- Guests --}}
                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Guests</div>

                    @php
                    $guests = $event->guests ?? collect();
                    $headcount = $guests->sum('party_size');
                    @endphp

                    @if($guests->isEmpty())
                    <div class="text-gray-500">No guests added.</div>
                    @else
                    <div class="mb-2 text-sm text-gray-600">
                        Total invitees: {{ $guests->count() }} • Estimated headcount: {{ $headcount }}
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border rounded">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left py-2 px-3">Name</th>
                                    <th class="text-left py-2 px-3">Contact</th>
                                    <th class="text-left py-2 px-3">Email</th>
                                    <th class="text-left py-2 px-3">Party Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guests as $g)
                                <tr class="border-t">
                                    <td class="py-2 px-3 font-medium">{{ $g->name }}</td>
                                    <td class="py-2 px-3">{{ $g->contact_number ?: '—' }}</td>
                                    <td class="py-2 px-3">{{ $g->email ?: '—' }}</td>
                                    <td class="py-2 px-3">{{ $g->party_size }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>


                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Selected Vendors</h3>
                    @if($event->vendors->count())
                    <ul class="list-disc pl-6">
                        @foreach($event->vendors as $v)
                        <li>{{ $v->name }} @if(!is_null($v->price)) — ₱{{ number_format($v->price,2) }} @endif</li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-gray-500">None selected.</div>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('customer.events.index') }}" class="underline">Back to events</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
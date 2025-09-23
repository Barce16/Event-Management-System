<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $event->name }}</h2>
            <a href="{{ route('customer.events.edit', $event) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-gray-600 text-sm">Date</div>
                        <div class="font-medium">
                            {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Status</div>
                        <div>
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
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
            </div>

            {{-- Package Details --}}
            @php
            $pkg = $event->package;
            $sty = is_array($pkg?->event_styling) ? $pkg->event_styling : [];
            @endphp
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg">Package Details</h3>

                @if($pkg)
                @if(!empty($pkg->description))
                <div class="mt-2">
                    <div class="text-gray-600 text-sm">Description</div>
                    <div class="text-gray-800 whitespace-pre-line">{{ $pkg->description }}</div>
                </div>
                @endif

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded border bg-gray-50 p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Coordination</div>
                        <div class="mt-1 text-sm text-gray-800 whitespace-pre-line">
                            {{ $pkg->coordination ?: '—' }}
                        </div>
                        <div class="mt-2 font-semibold">
                            ₱{{ number_format($pkg->coordination_price ?? 25000, 2) }}
                        </div>
                    </div>

                    <div class="rounded border bg-gray-50 p-3">
                        <div class="text-xs uppercase tracking-wide text-gray-500">Event Styling</div>
                        @if(empty($sty))
                        <div class="mt-1 text-sm text-gray-500">—</div>
                        @else
                        <ul class="mt-1 text-sm text-gray-800 list-disc pl-5 space-y-0.5">
                            @foreach($sty as $item)
                            @if(trim($item) !== '')
                            <li>{{ $item }}</li>
                            @endif
                            @endforeach
                        </ul>
                        @endif
                        <div class="mt-2 font-semibold">
                            ₱{{ number_format($pkg->event_styling_price ?? 55000, 2) }}
                        </div>
                    </div>
                </div>
                @else
                <div class="text-gray-500 mt-2">No package information.</div>
                @endif
            </div>

            {{-- Selected Inclusions for this Event --}}
            @php
            // Make sure relations are loaded: $event->loadMissing('inclusions');
            $incs = $event->inclusions ?? collect();
            $incSubtotal = $incs->sum(fn($i) => (float)($i->pivot->price_snapshot ?? $i->price ?? 0));
            @endphp
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg">Selected Inclusions</h3>

                @if($incs->isEmpty())
                <div class="text-gray-500">No inclusions selected.</div>
                @else
                <ul class="mt-3 space-y-2">
                    @foreach($incs as $inc)
                    @php
                    $price = $inc->pivot->price_snapshot ?? $inc->price;
                    $notes = trim((string)($inc->notes ?? ''));
                    $lines = $notes !== '' ? preg_split('/\r\n|\r|\n/', $notes) : [];
                    @endphp
                    <li class="rounded border p-3 bg-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-medium text-gray-900">
                                    {{ $inc->name }}
                                    @if($inc->category)
                                    <span class="ml-2 text-xs text-gray-500">• {{ $inc->category }}</span>
                                    @endif
                                </div>
                                @if(!empty($lines))
                                <ul class="mt-1 text-xs text-gray-700 list-disc pl-5 space-y-0.5">
                                    @foreach($lines as $line)
                                    @if(trim($line) !== '')
                                    <li>{{ $line }}</li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @if(!is_null($price))
                            <div class="shrink-0 text-sm font-semibold">
                                ₱{{ number_format($price, 2) }}
                            </div>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>

                {{-- Totals (based on current package coordination/styling and selected inclusions snapshot) --}}
                <div class="mt-4 rounded border bg-gray-50 p-3 text-sm text-gray-800">
                    <div class="flex items-center justify-between">
                        <span>Inclusions Subtotal</span>
                        <span class="font-semibold">₱{{ number_format($incSubtotal, 2) }}</span>
                    </div>
                    @if($pkg)
                    <div class="flex items-center justify-between mt-1">
                        <span>Coordination</span>
                        <span class="font-semibold">₱{{ number_format($pkg->coordination_price ?? 25000, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span>Event Styling</span>
                        <span class="font-semibold">₱{{ number_format($pkg->event_styling_price ?? 55000, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2 border-t pt-2">
                        <span>Estimated Total</span>
                        @php
                        $grand = $incSubtotal + (float)($pkg->coordination_price ?? 25000) +
                        (float)($pkg->event_styling_price ?? 55000);
                        @endphp
                        <span class="font-bold text-2xl">₱{{ number_format($grand, 2) }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Guests --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
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

            <div>
                <a href="{{ route('customer.events.index') }}" class="underline">Back to events</a>
            </div>
        </div>
    </div>
</x-app-layout>
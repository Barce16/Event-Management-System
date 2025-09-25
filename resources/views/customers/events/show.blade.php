<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">{{ $event->name }}</h2>

            @if($event->status === 'requested')
            <a href="{{ route('customer.events.edit', $event) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Edit</a>
            @endif
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                {{-- Display Downpayment Request if Event is Approved --}}
                @if($event->status === 'approved')
                <div class="bg-yellow-100 text-yellow-800 p-2 rounded-lg mb-5">
                    <div class="font-semibold text-lg">Please pay your downpayment to proceed with the scheduling of the
                        meeting.</div>
                    @if($event->billing)
                    <div class="mt-2">
                        Downpayment Amount: <strong>₱{{ number_format($event->billing->downpayment_amount, 2)
                            }}</strong>
                    </div>
                    @else
                    <div class="mt-2 text-red-500">No billing information available for this event.</div>
                    @endif

                    <div class="my-4">
                        <a href="{{ route('customer.payments.create', ['event' => $event->id]) }}"
                            class="px-4 py-2 bg-emerald-700 text-white rounded hover:bg-emerald-600">
                            Pay Now
                        </a>
                    </div>
                </div>
                @endif
                {{-- Display Downpayment Request if Event is Meeting --}}
                @if($event->status === 'meeting')
                <div class="bg-blue-100 text-blue-800 p-4 rounded-lg mb-5">
                    <div class="font-semibold text-lg">Your downpayment has been confirmed. Please contact us to
                        schedule the meeting.</div>
                    <div class="mt-2">
                        For scheduling, please reach us at: <strong>09173062531</strong>
                    </div>
                </div>
                @endif

                {{-- Display Downpayment Rejected Message --}}
                @if($event->billing && $event->billing->downpayment_amount > 0 &&
                $event->billing->payment()->where('status', 'rejected')->exists())
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-5">
                    <div class="font-semibold text-lg">Your downpayment has been rejected. Please contact us for further
                        details.</div>

                    @if($event->billing)
                    <div class="mt-2">
                        Downpayment Amount: <strong>₱{{ number_format($event->billing->downpayment_amount, 2)
                            }}</strong>
                    </div>
                    @else
                    <div class="mt-2 text-red-500">No billing information available for this event.</div>
                    @endif

                    <div class="my-4">
                        <a href="{{ route('customer.payments.create', ['event' => $event->id]) }}"
                            class="px-4 py-2 bg-rose-950 text-white rounded hover:bg-rose-600">
                            Pay Now
                        </a>
                    </div>
                </div>
                @endif


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

            <div>
                <a href="{{ route('customer.events.index') }}" class="underline">Back to events</a>
            </div>
        </div>
    </div>
</x-app-layout>
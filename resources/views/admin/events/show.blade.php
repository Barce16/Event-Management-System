<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Event: {{ $event->name }}
            </h2>

            @php
            $statusKey = strtolower((string) $event->status);
            $badge = match($statusKey) {
            'requested' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'scheduled' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'request_meeting' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
            };
            @endphp
            <span class="px-2 py-1 rounded text-xs {{ $badge }}">
                {{ ucfirst($event->status) }}
            </span>
        </div>
    </x-slot>

    @php
    // --- Compute grand total safely (server-side) ---
    $coord = (float) optional($event->package)->coordination_price ?? 25000;
    $styl = (float) optional($event->package)->event_styling_price ?? 55000;
    $incSubtotal = (float) $event->inclusions->sum(fn($i) => (float) ($i->pivot->price_snapshot ?? 0));
    $grandTotal = $coord + $styl + $incSubtotal;
    @endphp

    <div class="py-6" x-data="{
    grandTotal: {{ json_encode($grandTotal) }},
    status: @js($event->status),
    showApprove: false,
    showReject: false,
    downpayment: 0,
    selected: new Set(),
    query: '',
    isDownpaymentPending: @js($isDownpaymentPending),
    details: {},

    fmt(n) {
        return Number(n || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    },

    statusLabel() {
        return formatStatus(this.status);
    },

    openApprove() {
        this.downpayment = Math.max(this.grandTotal * 0.5, 0); // default 50%
        this.showReject = false;
        this.showApprove = true;
    },

    openReject() {
        this.showApprove = false;
        this.showReject = true;
    },

    toggle(p) {
        const id = p.id;
        if (this.selected.has(id)) {
            this.selected.delete(id);
        } else {
            this.selected.add(id);
        }
    },
}">
        <!-- Main container -->
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary + Actions --}}

            <template x-if="status === 'request_meeting' && isDownpaymentPending">
                <div class="bg-yellow-100 p-4 rounded-md mt-4 flex flex-col gap-2">
                    <div class="text-gray-600 text-sm">
                        Customer has submitted the downpayment of ₱{{ number_format($paymentAmount, 2) }}.
                        Please verify the payment before scheduling a meeting.
                    </div>
                    <a href="{{ route('admin.payment.verification', $event) }}"
                        class="px-4 py-2 mt-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 w-fit">
                        Verify Payment
                    </a>
                </div>
            </template>


            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="space-y-2">
                        <div>
                            <div class="text-gray-600 text-sm">Date</div>
                            <div class="font-medium">
                                {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-600 text-sm">Customer</div>
                            <div class="flex items-center gap-2">
                                @php
                                $avatar = optional(optional($event->customer)->user)->profile_photo_url
                                ?? 'https://ui-avatars.com/api/?name=' . urlencode($event->customer->customer_name ??
                                'Unknown') . '&background=E5E7EB&color=111827';
                                @endphp
                                <img src="{{ $avatar }}" class="h-7 w-7 rounded-full object-cover" alt="Avatar">
                                <div class="font-medium">
                                    {{ $event->customer->customer_name ?? 'Unknown' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-gray-600 text-sm">Package</div>
                            <div class="font-medium">{{ $event->package->name ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- ACTIONS (Approve/Reject) --}}
                    <template x-if="status === 'requested'">
                        <div class="flex items-center gap-2">
                            <button type="button"
                                class="px-3 py-2 rounded-md bg-emerald-900/80 text-white hover:bg-emerald-600"
                                @click="openApprove()">
                                Approve
                            </button>
                            <button type="button"
                                class="px-3 py-2 rounded-md bg-rose-900/80 text-white hover:bg-red-600"
                                @click="openReject()">
                                Reject
                            </button>
                        </div>
                    </template>
                    <template x-if="status !== 'requested'">
                        <div class="text-sm text-gray-600">
                            Status is <span class="font-bold" x-text="statusLabel()"></span>
                        </div>

                    </template>

                    <template x-if="statusLabel() === 'Meeting'">
                        <form method="POST" action="{{ route('admin.events.confirm', $event) }}">
                            @csrf
                            <button
                                class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-opacity-50 transition duration-200">
                                Confirm
                            </button>
                        </form>
                    </template>


                    <template x-if="statusLabel() === 'Scheduled'">
                        <div class="space-x-4">
                            <a href="{{ route('admin.event.guests', $event) }}" class="underline">Guests</a>
                            <a href="{{ route('admin.event.staffs', $event) }}" class="underline">Staffs</a>
                        </div>
                    </template>

                </div>

                {{-- Approve inline panel --}}
                <div x-show="showApprove" x-transition x-cloak class="mt-4 border rounded-lg p-4 bg-emerald-50">
                    <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="space-y-3">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">Approve Event</div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="text-xs uppercase tracking-wide text-gray-500">Grand Total</div>
                                <div class="text-lg font-semibold">₱<span x-text="fmt(grandTotal)"></span></div>
                            </div>

                            <div>
                                <x-input-label for="downpayment_amount" value="Downpayment (₱)" />
                                <input type="number" step="0.01" min="0" x-model.number="downpayment"
                                    name="downpayment_amount" id="downpayment_amount"
                                    class="mt-1 w-full border rounded px-3 py-2" />
                                <p class="text-xs text-gray-600 mt-1">
                                    Default is 50% of grand total.
                                </p>
                            </div>

                            <div>
                                <div class="text-xs uppercase tracking-wide text-gray-500">Remaining</div>
                                <div class="text-lg font-semibold">
                                    ₱<span x-text="fmt(Math.max(grandTotal - (downpayment || 0), 0))"></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" class="px-3 py-2 border rounded"
                                @click="showApprove=false">Cancel</button>
                            <button class="px-4 py-2 bg-emerald-700 text-white rounded hover:bg-emerald-600">
                                Confirm Approve
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Reject inline panel --}}
                <div x-show="showReject" x-transition x-cloak class="mt-4 border rounded-lg p-4 bg-red-50">
                    <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="space-y-3">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">Reject Event</div>
                        </div>

                        <div>
                            <x-input-label for="rejection_reason" value="Reason (optional)" />
                            <textarea id="rejection_reason" name="rejection_reason" rows="3"
                                class="mt-1 w-full border rounded px-3 py-2"
                                placeholder="Why is this request rejected?"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" class="px-3 py-2 border rounded"
                                @click="showReject=false">Cancel</button>
                            <button class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-600">
                                Confirm Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Package details & inclusions --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold mb-3">Package Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Package</div>
                        <div class="font-medium">{{ $event->package->name ?? '—' }}</div>

                        <div class="mt-3 text-sm text-gray-600">Coordination</div>
                        <div class="whitespace-pre-line text-sm">
                            {{ $event->package->coordination ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Event Styling</div>
                        @if(is_array(optional($event->package)->event_styling) && count($event->package->event_styling))
                        <ul class="list-disc pl-5 text-sm space-y-0.5">
                            @foreach($event->package->event_styling as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @else
                        <div class="text-gray-500 text-sm">—</div>
                        @endif

                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-sm text-gray-600 mb-1">Selected Inclusions</div>
                    @if($event->inclusions->isEmpty())
                    <div class="text-gray-500 text-sm">—</div>
                    @else
                    <ul class="space-y-2">
                        @foreach($event->inclusions as $inc)
                        <li class="border rounded p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $inc->name }}
                                        @if($inc->category)
                                        <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                                        @endif
                                    </div>

                                    {{-- Inclusion Notes --}}
                                    @if(trim($inc->notes))
                                    <div class="text-xs text-gray-500 mt-1">
                                        Notes: {{ $inc->notes }}
                                    </div>
                                    @endif
                                </div>

                                {{-- Inclusion Price --}}
                                @if(!is_null(optional($inc->pivot)->price_snapshot))
                                <div class="text-sm font-semibold whitespace-nowrap">
                                    ₱{{ number_format((float)$inc->pivot->price_snapshot, 2) }}
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-gray-600">Coordination Price</div>
                        <div class="font-medium">
                            ₱{{ number_format($coord, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Event Styling Price</div>
                        <div class="font-medium">
                            ₱{{ number_format($styl, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-600">Grand Total</div>
                        <div class="font-semibold">
                            ₱{{ number_format($grandTotal, 2) }}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        function formatStatus(status) {
        return status.replace(/_/g, ' ')  
                     .toLowerCase()  
                     .replace(/\b\w/g, function(char) { return char.toUpperCase(); }); 
    }
    </script>
</x-app-layout>
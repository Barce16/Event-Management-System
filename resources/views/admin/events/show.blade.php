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
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xl font-semibold">{{ $event->name }}</div>
                        <div class="text-gray-600">
                            Package: <span class="font-medium">{{ $event->package?->name ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <form action="{{ route('admin.events.status', $event) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="border rounded px-3 py-2" onchange="this.form.submit()">
                            @foreach(['requested','approved','scheduled','completed','cancelled'] as $s)
                            <option value="{{ $s }}" @selected($event->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Basics --}}
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
                        <div>{{ is_null($event->budget) ? '—' : '₱'.number_format($event->budget, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Guests</div>
                        <div>{{ $event->guest_count ?: '—' }}</div>
                    </div>
                </div>

                {{-- Customer --}}
                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Customer</div>
                    <div class="p-4 rounded border">
                        <div class="font-medium">{{ $event->customer?->customer_name ?? '—' }}</div>
                        <div class="text-gray-600">{{ $event->customer?->email ?? '' }}</div>
                    </div>
                </div>

                {{-- Vendors --}}
                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Selected Vendors</div>

                    @php
                    $vendors = $event->vendors ?? collect();
                    $total = $vendors->sum(fn($v) => $v->pivot->price ?? $v->price ?? 0);
                    @endphp

                    @if($vendors->isEmpty())
                    <div class="text-gray-500">No vendors selected.</div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border rounded">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left py-2 px-3">Vendor</th>
                                    <th class="text-left py-2 px-3">Category</th>
                                    <th class="text-left py-2 px-3">Contact</th>
                                    <th class="text-right py-2 px-3">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendors as $v)
                                @php
                                $price = $v->pivot->price ?? $v->price ?? 0;
                                @endphp
                                <tr class="border-t">
                                    <td class="py-2 px-3 font-medium">{{ $v->name }}</td>
                                    <td class="py-2 px-3">{{ $v->category ?? '—' }}</td>
                                    <td class="py-2 px-3 text-gray-600">
                                        {{ $v->contact_person ?: '—' }}
                                        @if($v->phone) · {{ $v->phone }} @endif
                                    </td>
                                    <td class="py-2 px-3 text-right">₱{{ number_format($price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t bg-gray-50">
                                    <td colspan="3" class="py-2 px-3 text-right font-semibold">Estimated Total</td>
                                    <td class="py-2 px-3 text-right font-semibold">₱{{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- Notes --}}
                <div class="mt-6">
                    <div class="text-gray-500 text-sm mb-1">Notes</div>
                    <div class="whitespace-pre-wrap">{{ $event->notes ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
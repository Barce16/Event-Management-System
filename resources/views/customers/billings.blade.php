<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Billings</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @forelse($eventsWithBillings as $event)
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl">{{ $event->name }}</h3>
                <p class="text-gray-600">Event Date: {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}
                </p>

                <div class="mt-2">
                    <strong class="text-gray-700">Outstanding Amount: </strong>
                    ₱{{ number_format($event->billing->total_amount, 2) }}
                </div>

                <div class="mt-4">
                    @if($event->billing->total_amount > 0)
                    <div class="bg-yellow-100 p-4 rounded-md">
                        <span class="text-yellow-800 font-semibold">Status: Incomplete</span>
                    </div>
                    <a href="{{ route('customer.payments.create', ['event' => $event->id]) }}"
                        class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">
                        Make Payment
                    </a>
                    @else
                    <div class="bg-green-100 p-4 rounded-md">
                        <span class="text-green-800 font-semibold">Status: Completed</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500">No incomplete billings found.</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
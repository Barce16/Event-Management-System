<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payments</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @foreach($payments as $payment)
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-semibold text-xl">{{ $payment->event->name }} ({{ $payment->event->event_date }})</h3>

                <div class="mt-4">
                    <strong class="text-gray-700">Amount Paid:</strong>
                    ₱{{ number_format($payment->amount, 2) }}
                </div>

                <div class="mt-2">
                    <strong class="text-gray-700">Payment Method:</strong>
                    {{ ucfirst($payment->payment_method) }}
                </div>

                <div class="mt-4">
                    <strong class="text-gray-700">Payment Proof:</strong>
                    <div class="mt-2">
                        <img src="{{ Storage::url($payment->payment_image) }}" alt="Payment Proof"
                            class="w-64 h-48 object-cover rounded-lg shadow-md">
                    </div>
                </div>

                <div class="mt-4 flex justify-between">
                    <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">
                            Approve Payment
                        </button>
                    </form>

                    <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">
                            Reject Payment
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($payments->isEmpty())
            <div class="bg-white p-4 rounded-lg shadow-md">
                <p class="text-center text-gray-500">No pending payments.</p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
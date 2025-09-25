<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">
                Verify Payment for Event: {{ $event->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white rounded-lg shadow-lg p-8 space-y-6">
                <h3 class="font-semibold text-lg mb-6 text-gray-800 border-b pb-3">Payment Details</h3>

                <div class="space-y-6">
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <strong class="text-gray-700">Payment Proof:</strong>
                            <div class="mt-2">
                                <img src="{{ Storage::url($payment->payment_image) }}" alt="Payment Proof"
                                    class="w-full max-w-xs h-48 object-cover rounded-lg shadow-md">
                            </div>
                        </div>

                        <div class="flex-1">
                            <strong class="text-gray-700">Amount:</strong>
                            <div class="font-medium text-xl text-gray-900 mt-2">₱{{ number_format($payment->amount, 2)
                                }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <strong class="text-gray-700">Payment Method:</strong>
                            <div class="font-medium text-gray-800 mt-2">
                                {{ ucwords(str_replace('_', ' ', strtolower($payment->payment_method))) }}
                            </div>
                        </div>
                    </div>

                    <!-- Buttons section aligned to the bottom right using Flexbox -->
                    <div class="flex justify-end gap-4 mt-8">
                        <form method="POST" action="{{ route('admin.payments.approve', $event->id) }}">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-black text-white rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-opacity-50 transition duration-200">
                                Approve Payment
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.payments.reject', $event->id) }}">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-gray-300 text-gray-950 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50 transition duration-200">
                                Reject Payment
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
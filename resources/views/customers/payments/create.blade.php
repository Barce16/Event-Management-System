<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Payment for Event: {{ $event->name }}</h2>
            <a href="{{ route('customer.events.index') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Back to
                Events</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Payment Form --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-lg">Submit Payment Proof</h3>

                <form method="POST" action="{{ route('customer.payments.store', $event) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mt-4">
                        <div class="text-gray-600 text-sm">Event: <strong>{{ $event->name }}</strong></div>

                        @if ($event->billing)
                        <div class="text-gray-600 text-sm">Downpayment Due: <strong>₱{{
                                number_format($event->billing->downpayment_amount, 2) }}</strong></div>
                        @else
                        <div class="text-gray-600 text-sm">Downpayment Due: <strong>₱0.00</strong></div>
                        @endif
                    </div>

                    {{-- File Upload --}}
                    <div class="mt-4">
                        <x-input-label for="payment_receipt" value="Payment Proof" />
                        <input id="payment_receipt" name="payment_receipt" type="file" class="mt-1 block w-full"
                            required onchange="previewImage(event)" />
                        @error('payment_receipt')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Image Preview --}}
                    <div class="mt-4" id="image-preview-container" style="display: none;">
                        <h4 class="font-semibold text-sm">Preview of Payment Proof:</h4>
                        <img id="image-preview" class="mt-2 w-full max-w-xs" />
                    </div>

                    {{-- Amount to Pay --}}
                    <div class="mt-4">
                        <x-input-label for="amount" value="Amount to Pay (₱)" />
                        <input id="amount" name="amount" type="number" step="0.01" min="0"
                            value="{{ old('amount', $event->downpayment_amount) }}" class="mt-1 block w-full"
                            required />
                        @error('amount')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Payment Method --}}
                    <div class="mt-4">
                        <x-input-label for="payment_method" value="Payment Method" />
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="paypal">PayPal</option>
                            <option value="physical_payment">Physical Payment (In-Hand)</option>
                        </select>

                        @error('payment_method')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Preview image before submitting
        function previewImage(event) {
            const previewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            
            const file = event.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
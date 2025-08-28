<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Request New Event</h2>
            <a href="{{ route('customer.events.index') }}" class="underline">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded">
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('customer.events.store') }}"
                class="bg-white shadow-sm rounded-lg p-6 space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" value="Event Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}"
                        required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="event_type_id" value="Event Type" />
                        <select id="event_type_id" name="event_type_id" class="mt-1 w-full border rounded px-3 py-2"
                            required>
                            <option value="">Select type…</option>
                            @foreach ($eventTypes as $t)
                            <option value="{{ $t->id }}" @selected(old('event_type_id')==$t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="event_date" value="Event Date" />
                        <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                            value="{{ old('event_date') }}" required />
                    </div>

                    <div>
                        <x-input-label for="venue" value="Venue" />
                        <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                            value="{{ old('venue') }}" />
                    </div>

                    <div>
                        <x-input-label for="theme" value="Theme" />
                        <x-text-input id="theme" name="theme" type="text" class="mt-1 block w-full"
                            value="{{ old('theme') }}" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="budget" value="Budget (₱)" />
                        <x-text-input id="budget" name="budget" type="number" step="0.01" min="0"
                            class="mt-1 block w-full" value="{{ old('budget') }}" />
                    </div>
                    <div>
                        <x-input-label for="guest_count" value="Guest Count" />
                        <x-text-input id="guest_count" name="guest_count" type="number" min="1"
                            class="mt-1 block w-full" value="{{ old('guest_count') }}" />
                    </div>
                </div>

                <div>
                    <x-input-label for="notes" value="Notes / Special Requests" />
                    <textarea id="notes" name="notes" rows="4"
                        class="mt-1 w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <x-input-label value="Add-on Services" />
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($services as $s)
                        <label class="flex items-start gap-2 p-2 border rounded">
                            <input type="checkbox" name="services[]" value="{{ $s->id }}" @checked(in_array($s->id,
                            old('services', $selectedServices ?? [])))>
                            <span>
                                <span class="font-medium">{{ $s->name }}</span>
                                @if($s->base_price !== null)
                                <span class="block text-sm text-gray-500">₱{{ number_format($s->base_price, 2) }}</span>
                                @endif
                                @if($s->description)
                                <span class="block text-xs text-gray-500">{{ $s->description }}</span>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('services')" class="mt-2" />
                </div>


                <div class="pt-4 flex items-center gap-3">
                    <x-primary-button>Submit Request</x-primary-button>
                    <a href="{{ route('customer.events.index') }}" class="underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
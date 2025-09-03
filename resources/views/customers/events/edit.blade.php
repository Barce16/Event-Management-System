<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Event</h2>
    </x-slot>

    <div class="py-6" x-data="editEventForm()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('customer.events.update', $event) }}">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Event Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name', $event->name) }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="event_date" value="Event Date" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                                value="{{ old('event_date', \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d')) }}"
                                required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="package_id" value="Package" />
                            <select id="package_id" name="package_id" class="mt-1 w-full border rounded px-3 py-2"
                                x-model.number="selectedPackage" @change="applyPackageDefaults">
                                <option value="">-- Choose Package --</option>
                                @foreach($packages as $p)
                                <option value="{{ $p->id }}" @selected(old('package_id', $event->package_id) ==
                                    $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('package_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="venue" value="Venue" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                                value="{{ old('venue', $event->venue) }}" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="theme" value="Theme" />
                            <x-text-input id="theme" name="theme" type="text" class="mt-1 block w-full"
                                value="{{ old('theme', $event->theme) }}" />
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="guest_count" value="Guest Count" />
                            <x-text-input id="guest_count" name="guest_count" type="number" min="1"
                                class="mt-1 block w-full" value="{{ old('guest_count', $event->guest_count) }}" />
                            <x-input-error :messages="$errors->get('guest_count')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="budget" value="Budget (optional)" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" min="0"
                                class="mt-1 block w-full" value="{{ old('budget', $event->budget) }}" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 w-full border rounded px-3 py-2">{{ old('notes', $event->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Vendors --}}
                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">Vendors</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @php $oldVendors = collect(old('vendors', $selectedVendorIds ??
                            []))->map(fn($i)=>(int)$i)->all(); @endphp
                            @foreach($vendors as $v)
                            @php $checked = in_array($v->id, $oldVendors, true); @endphp
                            <label class="flex items-center gap-2 border rounded px-3 py-2">
                                <input type="checkbox" name="vendors[]" value="{{ $v->id }}"
                                    class="rounded border-gray-300" x-ref="vendor_{{ $v->id }}" @checked($checked)
                                    @change="toggleManual({{ $v->id }})">
                                <span class="flex-1">
                                    <span class="font-medium">{{ $v->name }}</span>
                                    @if(!is_null($v->price))
                                    <span class="text-gray-500"> — ₱{{ number_format($v->price,2) }}</span>
                                    @endif
                                </span>
                            </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('vendors')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('customer.events.show',$event) }}" class="px-4 py-2 border rounded">Cancel</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editEventForm() {
            const packageVendors = {
                @foreach($packages as $p)
                    {{ $p->id }}: [@foreach($p->vendors as $pv) {{ $pv->id }}@if(!$loop->last),@endif @endforeach],
                @endforeach
            };
            return {
                selectedPackage: Number(@json(old('package_id', $event->package_id)) || 0),
                manual: new Set(),
                applyPackageDefaults() {
                    this.manual.clear();
                    const defaults = new Set(packageVendors[this.selectedPackage] || []);
                    Object.keys(this.$refs).forEach(k => {
                        if (!k.startsWith('vendor_')) return;
                        const el = this.$refs[k];
                        const id = Number(k.replace('vendor_', ''));
                        el.checked = defaults.has(id);
                    });
                },
                toggleManual(id) { this.manual.add(id); },
            }
        }
    </script>
</x-app-layout>
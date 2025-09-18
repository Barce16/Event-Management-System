<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Request New Event</h2>
    </x-slot>

    <div class="py-6" x-data="eventForm()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('customer.events.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Event Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                value="{{ old('name') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="event_date" value="Event Date" />
                            <x-text-input id="event_date" name="event_date" type="date" class="mt-1 block w-full"
                                value="{{ old('event_date') }}" required />
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="package_id" value="Package" />
                            <select id="package_id" name="package_id" class="mt-1 w-full border rounded px-3 py-2"
                                x-model.number="selectedPackage" @change="applyPackageDefaults">
                                <option value="">-- Choose Package --</option>
                                @foreach($packages as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('package_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="venue" value="Venue" />
                            <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full"
                                value="{{ old('venue') }}" />
                            <x-input-error :messages="$errors->get('venue')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="theme" value="Theme" />
                            <x-text-input id="theme" name="theme" type="text" class="mt-1 block w-full"
                                value="{{ old('theme') }}" />
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                        {{-- <div>
                            <x-input-label for="guest_count" value="Guest Count" />
                            <x-text-input id="guest_count" name="guest_count" type="number" min="1"
                                class="mt-1 block w-full" value="{{ old('guest_count') }}" />
                            <x-input-error :messages="$errors->get('guest_count')" class="mt-2" />
                        </div> --}}

                        <div class="md:col-span-2">
                            <x-input-label for="budget" value="Budget (optional)" />
                            <x-text-input id="budget" name="budget" type="number" step="0.01" min="0"
                                class="mt-1 block w-full" value="{{ old('budget') }}" />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        {{-- Guests section --}}
                        <div class="md:col-span-2">
                            <div x-data="{
                                items: @js(old('guests', [])),
                                draft: {name:'', email:'', contact_number:'', party_size:1},
                                add() {
                                    if(this.draft.name || this.draft.email || this.draft.contact_number){
                                        this.items.push({...this.draft, saved: true});
                                        this.draft = {name:'', email:'', contact_number:'', party_size:1};
                                    }
                                },
                                remove(i){ this.items.splice(i,1); }
                            }" class="bg-white rounded-lg shadow-sm space-y-3">

                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold">Guests</h4>
                                    <button type="button" @click="add()" class="px-3 py-2 border rounded text-sm">Add
                                        Guest</button>
                                </div>

                                {{-- Draft row (always white) --}}
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-2 border p-4 mt-3 rounded-lg bg-white">
                                    <div class="md:col-span-3">
                                        <x-input-label>Name</x-input-label>
                                        <input type="text" class="w-full border rounded px-3 py-2" name="draft_name"
                                            x-model="draft.name" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label>Email</x-input-label>
                                        <input type="email" class="w-full border rounded px-3 py-2" name="draft_email"
                                            x-model="draft.email" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label>Contact</x-input-label>
                                        <input type="text" class="w-full border rounded px-3 py-2" name="draft_contact"
                                            x-model="draft.contact_number" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label>Party Size</x-input-label>
                                        <input type="number" min="1" class="w-full border rounded px-3 py-2"
                                            name="draft_party" x-model.number="draft.party_size" />
                                    </div>
                                </div>

                                {{-- Saved guests (tinted green) --}}
                                <template x-for="(g, i) in items" :key="i">
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-5 gap-2 border p-4 mt-3 rounded-lg bg-slate-800/20">
                                        <div class="md:col-span-3">
                                            <x-input-label>Name</x-input-label>
                                            <input type="text" class="w-full border rounded px-3 py-2"
                                                :name="`guests[${i}][name]`" x-model="g.name" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label>Email</x-input-label>
                                            <input type="email" class="w-full border rounded px-3 py-2"
                                                :name="`guests[${i}][email]`" x-model="g.email" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label>Contact</x-input-label>
                                            <input type="text" class="w-full border rounded px-3 py-2"
                                                :name="`guests[${i}][contact_number]`" x-model="g.contact_number" />
                                        </div>
                                        <div class="md:col-span-2">
                                            <x-input-label>Party Size</x-input-label>
                                            <input type="number" min="1" class="w-full border rounded px-3 py-2"
                                                :name="`guests[${i}][party_size]`" x-model.number="g.party_size" />
                                        </div>
                                        <div class="md:col-span-1 text-right flex items-end justify-center">
                                            <button type="button" @click="remove(i)"
                                                class="px-4 py-2 bg-red-700 text-white rounded">Remove</button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>

                    {{-- Vendors --}}
                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">Vendors (Package defaults are auto-selected—adjust as you like)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($vendors as $v)
                            <label class="flex items-center gap-2 border rounded px-3 py-2">
                                <input type="checkbox" name="vendors[]" value="{{ $v->id }}"
                                    class="rounded border-gray-300" :checked="vendorChecked({{ $v->id }})"
                                    @change="toggleManual({{ $v->id }})" x-ref="vendor_{{ $v->id }}">
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
                        <a href="{{ route('customer.events.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                        <button class="px-4 py-2 bg-gray-800 text-white rounded">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Alpine data: package -> default vendor IDs --}}
    <script>
        function eventForm() {
            const packageVendors = {
                @foreach($packages as $p)
                    {{ $p->id }}: [@foreach($p->vendors as $pv) {{ $pv->id }}@if(!$loop->last),@endif @endforeach],
                @endforeach
            };
            return {
                selectedPackage: Number(@json(old('package_id')) || 0),
                manual: new Set(), 
                vendorChecked(id) {
                    if (this.manual.has(id)) {

                        const el = this.$refs['vendor_' + id];
                        return !!(el && el.checked);
                    }
                    const defaults = packageVendors[this.selectedPackage] || [];
                    return defaults.includes(id);
                },
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
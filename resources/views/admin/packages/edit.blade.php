<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-3xl">
        <h3 class="text-lg font-semibold">Edit Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.update', $package) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <x-input-label for="name" value="Package Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    value="{{ old('name', $package->name) }}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3"
                    class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $package->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- Inclusion --}}
            <style>
                [x-cloak] {
                    display: none !important
                }
            </style>

            <div x-data="{
                selected: @js(
                old('inclusions',
                    $package->inclusions->map(fn($i) => ['id' => $i->id, 'notes' => $i->pivot->notes])->values()
                )
                ),
                names: @js($inclusions->pluck('name','id')),
                has(id){ return this.selected.findIndex(x => Number(x.id) === Number(id)) !== -1; },
                toggle(id){
                const idx = this.selected.findIndex(x => Number(x.id) === Number(id));
                if (idx > -1) this.selected.splice(idx, 1);
                else this.selected.push({ id, notes: '' });
                this.$nextTick(() => this.reflowAllTextareas());
                },
                autoResize(el){
                el.style.height = 'auto';
                el.style.overflow = 'hidden';
                el.style.height = el.scrollHeight + 'px';
                },
                reflowAllTextareas(){
                this.$root.querySelectorAll('textarea[data-autogrow]')
                    .forEach(t => this.autoResize(t));
                }
            }" x-init="$nextTick(() => reflowAllTextareas())" x-cloak
                class="mt-6 bg-white rounded-lg shadow-sm p-4 space-y-3">
                <h4 class="font-semibold">Inclusions</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($inclusions as $inc)
                    <label class="flex items-center gap-2 border rounded px-3 py-2">
                        <input type="checkbox" @change="toggle({{ $inc->id }})" :checked="has({{ $inc->id }})"
                            class="rounded border-gray-300">
                        <span class="font-medium">{{ $inc->name }}</span>
                        @if($inc->category)
                        <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <template x-for="(row, idx) in selected" :key="row.id">
                    <div class="bg-emerald-800/10 border rounded-lg p-3">
                        <div class="text-sm font-medium mb-1" x-text="names[row.id] ?? 'Inclusion'"></div>

                        <textarea class="w-full border rounded px-3 py-2 text-sm resize-none overflow-hidden"
                            :name="`inclusions[${idx}][notes]`" x-model.trim="row.notes" rows="1" data-autogrow
                            x-init="$nextTick(() => autoResize($el))"
                            x-effect="row.notes; $nextTick(() => autoResize($el))" @input="autoResize($event.target)"
                            placeholder="Notes (e.g., 30 sets, digital printing; 1-page invitation; free layout)"></textarea>

                        <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                    </div>
                </template>

                <x-input-error :messages="$errors->get('inclusions')" />
                <x-input-error :messages="$errors->get('inclusions.*.id')" />
                <x-input-error :messages="$errors->get('inclusions.*.notes')" />
            </div>

            {{-- Coordination --}}
            <div>
                <x-input-label>Coordination</x-input-label>
                <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2"
                    placeholder="e.g., Full coordination on the day; timeline and supplier follow-ups">{{ old('coordination', $package->coordination ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('coordination')" />
            </div>

            {{-- Event Styling --}}
            <div>
                <x-input-label>Event Styling (one per line)</x-input-label>
                <textarea name="event_styling_text" rows="4" class="w-full border rounded px-3 py-2"
                    placeholder="Stage setup&#10;2-3 candles&#10;Aisle decor">{{ old('event_styling_text', isset($package) && is_array($package->event_styling) ? implode("\n", $package->event_styling) : '') }}</textarea>
                <x-input-error :messages="$errors->get('event_styling_text')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Package Price" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                        value="{{ old('price', $package->price) }}" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                        @checked(old('is_active', $package->is_active)) />
                    <x-input-label for="is_active" value="Active" />
                </div>
            </div>

            <div>
                <x-input-label for="vendors" value="Included Vendors" />
                @php $selected = old('vendors', $package->vendors->pluck('id')->all()); @endphp
                <select id="vendors" name="vendors[]" class="mt-1 w-full border rounded px-3 py-2" multiple size="8">
                    @foreach($vendors as $v)
                    <option value="{{ $v->id }}" @selected(collect($selected)->contains($v->id))>
                        {{ $v->name }} @if($v->price) — ₱{{ number_format($v->price, 2) }} @endif
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple vendors.</p>
                <x-input-error :messages="$errors->get('vendors')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Update Package</button>
            </div>
        </form>
    </div>
</x-admin.layouts.management>
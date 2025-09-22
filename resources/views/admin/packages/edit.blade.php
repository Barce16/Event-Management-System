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

            {{-- Inclusions --}}
            <style>
                [x-cloak] {
                    display: none !important
                }
            </style>

            <div x-data="inclusionsEditor({
                    initial: @js(
                        old('inclusions',
                            $package->inclusions
                                ->map(fn($i) => ['id'=>$i->id, 'notes'=>$i->pivot->notes])
                                ->values()
                        )
                    ),
                    names: @js($inclusions->pluck('name','id')),
                    categories: @js($inclusions->pluck('category','id')),
                    prices: @js($inclusions->pluck('price','id')),
                })" x-cloak class="mt-6 bg-white rounded-lg shadow-sm space-y-4">
                <h4 class="font-semibold text-base">Inclusions</h4>

                {{-- Picker --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($inclusions as $inc)
                    <label class="flex items-center justify-between gap-3 border rounded px-3 py-2 hover:bg-gray-50">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-gray-300" :checked="has({{ $inc->id }})"
                                @change="toggle({{ $inc->id }})">
                            <div class="min-w-0">
                                <div class="font-medium truncate">{{ $inc->name }}</div>
                                @if($inc->category)
                                <div class="text-[11px] text-gray-500">• {{ $inc->category }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 shrink-0">
                            ₱{{ number_format($inc->price, 2) }}
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Selected list w/ price preview --}}
                <template x-if="selected.length">
                    <div class="space-y-2">
                        <template x-for="(row, idx) in selected" :key="row.id">
                            <div class="border rounded-lg p-3 bg-emerald-800/5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium truncate" x-text="names[row.id] ?? 'Inclusion'">
                                        </div>
                                        <div class="text-[11px] text-gray-500" x-show="categories[row.id]"
                                            x-text="`• ${categories[row.id]}`"></div>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-800">
                                            ₱<span x-text="fmt(prices[row.id] ?? 0)"></span>
                                        </span>
                                        <button type="button" class="text-xs text-gray-500 hover:text-red-600"
                                            @click="remove(row.id)" aria-label="Remove inclusion">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <label class="sr-only" :for="`inc-notes-${row.id}`">Notes for inclusion</label>
                                <textarea :id="`inc-notes-${row.id}`"
                                    class="mt-2 w-full border rounded px-3 py-2 text-sm resize-none overflow-hidden focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                    :name="`inclusions[${idx}][notes]`" x-model.trim="row.notes" rows="1"
                                    x-init="autoResize($el)" @input="autoResize($event.target)"
                                    placeholder="Notes (e.g., 30 sets, digital printing; 1-page invitation; free layout)"></textarea>

                                <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                            </div>
                        </template>

                        {{-- Subtotal preview --}}
                        <div class="flex items-center justify-between mt-3 border-t pt-3">
                            <div class="text-sm text-gray-600">Inclusions Subtotal</div>
                            <div class="text-base font-semibold">
                                ₱<span x-text="fmt(subtotal())"></span>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Validation errors --}}
                <x-input-error :messages="$errors->get('inclusions')" />
                <x-input-error :messages="$errors->get('inclusions.*.id')" />
                <x-input-error :messages="$errors->get('inclusions.*.notes')" />
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('inclusionsEditor', ({ initial = [], names = {}, categories = {}, prices = {} }) => ({
                        selected: Array.isArray(initial) ? [...initial] : [],
                        names, categories, prices,
                        fmt(n){ return Number(n || 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}); },

                        init(){
                                this.$nextTick(() => this.reflowAllTextareas());
                            },

                        has(id){
                            id = Number(id);
                            return this.selected.findIndex(x => Number(x.id) === id) !== -1;
                        },
                        toggle(id){
                            id = Number(id);
                            const i = this.selected.findIndex(x => Number(x.id) === id);
                            if(i > -1){ this.selected.splice(i,1); }
                            else{ this.selected.push({ id, notes: '' }); }
                            this.$nextTick(() => this.reflowAllTextareas());
                        },
                        remove(id){
                            id = Number(id);
                            const i = this.selected.findIndex(x => Number(x.id) === id);
                            if(i > -1) this.selected.splice(i, 1);
                            this.$nextTick(() => this.reflowAllTextareas());
                        },

                        subtotal(){
                            return this.selected.reduce((sum, row) => {
                                const p = Number(this.prices[row.id] ?? 0);
                                return sum + (isNaN(p) ? 0 : p);
                            }, 0);
                        },

                        autoResize(el){
                            el.style.height = 'auto';
                            el.style.overflow = 'hidden';
                            el.style.height = el.scrollHeight + 'px';
                        },
                        reflowAllTextareas(){
                            this.$root.querySelectorAll('textarea').forEach(t => this.autoResize(t));
                        },
                    }));
                });
            </script>

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
                    placeholder="Stage setup&#10;2-3 candles&#10;Aisle decor">{{ old('event_styling_text', is_array($package->event_styling) ? implode("\n", $package->event_styling) : '') }}</textarea>
                <x-input-error :messages="$errors->get('event_styling_text')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label>Coordination Price</x-input-label>
                    <x-text-input type="number" step="0.01" min="0" name="coordination_price" class="w-full"
                        value="{{ old('coordination_price', $package->coordination_price ?? 25000) }}" />
                    <x-input-error :messages="$errors->get('coordination_price')" />
                </div>

                <div>
                    <x-input-label>Event Styling Price</x-input-label>
                    <x-text-input type="number" step="0.01" min="0" name="event_styling_price" class="w-full"
                        value="{{ old('event_styling_price', $package->event_styling_price ?? 55000) }}" />
                    <x-input-error :messages="$errors->get('event_styling_price')" />
                </div>
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

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Update Package</button>
            </div>
        </form>
    </div>
</x-admin.layouts.management>
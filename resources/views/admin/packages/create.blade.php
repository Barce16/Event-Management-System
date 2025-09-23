<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-3xl">
        <h3 class="text-lg font-semibold">New Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.store') }}" class="space-y-6">
            @csrf

            {{-- Basic info --}}
            <div>
                <x-input-label for="name" value="Package Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}"
                    required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3"
                    class="mt-1 w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- PRICING SECTION (one Alpine scope for everything below) --}}
            <div x-data="packagePricing({
                    initialInclusions: @js(old('inclusions', [])),
                    names:      @js($inclusions->pluck('name','id')),
                    categories: @js($inclusions->pluck('category','id')),
                    prices:     @js($inclusions->pluck('price','id')),
                    notes:      @js($inclusions->pluck('notes','id')),
                    defaults: {
                        coordinationPrice: @json(old('coordination_price', 25000)),
                        eventStylingPrice: @json(old('event_styling_price', 55000)),
                        packagePrice:      @json(old('price', 0)),
                        autoCalc:          @json(old('autoCalc', true))
                    }
                })" x-cloak class="space-y-6">
                {{-- Inclusions --}}
                <div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
                    <h4 class="font-semibold text-base">Inclusions</h4>

                    {{-- Picker --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($inclusions as $inc)
                        <label
                            class="flex items-center justify-between gap-3 border rounded px-3 py-2 hover:bg-gray-50">
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

                    {{-- Selected list with price and read-only notes --}}
                    <template x-if="selected.length">
                        <div class="space-y-2">
                            <template x-for="(row, idx) in selected" :key="row.id">
                                <div class="border rounded-lg p-3 bg-emerald-800/5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium truncate"
                                                x-text="names[row.id] ?? 'Inclusion'"></div>
                                            <div class="text-[11px] text-gray-500" x-show="categories[row.id]"
                                                x-text="`• ${categories[row.id]}`"></div>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <span class="px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-800">
                                                ₱<span x-text="fmt(prices[row.id] ?? 0)"></span>
                                            </span>
                                            <button type="button" class="text-xs text-gray-500 hover:text-red-600"
                                                @click="remove(row.id)">
                                                Remove
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Read-only notes (global from inclusion) --}}
                                    <template x-if="notes[row.id]">
                                        <div
                                            class="mt-2 text-xs text-gray-700 whitespace-pre-line border rounded px-3 py-2 bg-white/60">
                                            <span x-text="notes[row.id]"></span>
                                        </div>
                                    </template>

                                    {{-- Hidden field to submit only the inclusion ID --}}
                                    <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                                </div>
                            </template>

                            {{-- Subtotal --}}
                            <div class="flex items-center justify-between mt-3 border-t pt-3">
                                <div class="text-sm text-gray-600">Inclusions Subtotal</div>
                                <div class="text-base font-semibold">
                                    ₱<span x-text="fmt(subtotal())"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Validation errors for inclusions --}}
                    <x-input-error :messages="$errors->get('inclusions')" />
                    <x-input-error :messages="$errors->get('inclusions.*.id')" />
                </div>

                {{-- Coordination / Event Styling --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label>Coordination Price</x-input-label>
                        <x-text-input type="number" step="0.01" min="0" name="coordination_price" class="w-full"
                            x-model.number="coordinationPrice" />
                        <x-input-error :messages="$errors->get('coordination_price')" />
                    </div>

                    <div>
                        <x-input-label>Event Styling Price</x-input-label>
                        <x-text-input type="number" step="0.01" min="0" name="event_styling_price" class="w-full"
                            x-model.number="eventStylingPrice" />
                        <x-input-error :messages="$errors->get('event_styling_price')" />
                    </div>
                </div>

                <div>
                    <x-input-label>Coordination (notes/description)</x-input-label>
                    <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2 placeholder:text-sm"
                        placeholder="e.g.,
Full coordination on the day
timeline and supplier follow-ups">{{ old('coordination') }}</textarea>
                    <x-input-error :messages="$errors->get('coordination')" />
                </div>

                <div>
                    <x-input-label>Event Styling (one per line)</x-input-label>
                    <textarea name="event_styling_text" rows="4"
                        class="w-full border rounded px-3 py-2 placeholder:text-sm" placeholder="e.g.,
Stage setup
2-3 candles
Aisle decor">{{ old('event_styling_text') }}</textarea>
                    <x-input-error :messages="$errors->get('event_styling_text')" />
                </div>

                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Estimated Total (Inclusions + Coordination + Styling)</span>
                    <span class="text-base font-semibold">₱<span x-text="fmt(grandTotal())"></span></span>
                </div>

                {{-- Package Price --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" value="Package Price" />
                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                            x-model.number="packagePrice" x-bind:readonly="autoCalc" />
                        <div class="mt-2 flex items-center gap-2 text-sm">
                            <input type="checkbox" class="rounded border-gray-300" x-model="autoCalc" id="autoCalc">
                            <label for="autoCalc">Auto-calc from inclusions + coordination + styling</label>
                        </div>
                        <input type="hidden" name="autoCalc" x-model="autoCalc">
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-2 md:mt-6">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                            @checked(old('is_active', true)) />
                        <x-input-label for="is_active" value="Active" />
                    </div>
                </div>

                <div x-effect="if (autoCalc) { packagePrice = Number(grandTotal().toFixed(2)); }"></div>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Package</button>
            </div>
        </form>
    </div>

    <style>
        [x-cloak] {
            display: none !important
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
          Alpine.data('packagePricing', ({ initialInclusions = [], names = {}, categories = {}, prices = {}, notes = {}, defaults = {} }) => ({
            // Selected holds objects like { id: 1 }
            selected: Array.isArray(initialInclusions)
                ? initialInclusions.map(v => (typeof v === 'object' && v !== null) ? { id: Number(v.id) } : { id: Number(v) })
                : [],
            names, categories, prices, notes,

            coordinationPrice: Number(defaults.coordinationPrice ?? 25000),
            eventStylingPrice: Number(defaults.eventStylingPrice ?? 55000),
            packagePrice:      Number(defaults.packagePrice ?? 0),
            autoCalc:          Boolean(defaults.autoCalc ?? true),

            fmt(n) {
              return Number(n || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            has(id) {
              id = Number(id);
              return this.selected.findIndex(x => Number(x.id) === id) !== -1;
            },

            toggle(id) {
              id = Number(id);
              const i = this.selected.findIndex(x => Number(x.id) === id);
              if (i > -1) {
                this.selected.splice(i, 1);
              } else {
                this.selected.push({ id: id });
              }
              this.$nextTick(() => this.reflowAllTextareas());
            },

            remove(id) {
              id = Number(id);
              const i = this.selected.findIndex(x => Number(x.id) === id);
              if (i > -1) this.selected.splice(i, 1);
              this.$nextTick(() => this.reflowAllTextareas());
            },

            subtotal() {
              return this.selected.reduce((sum, row) => {
                const p = Number(this.prices[row.id] ?? 0);
                return sum + (isNaN(p) ? 0 : p);
              }, 0);
            },

            grandTotal() {
              return (this.subtotal() || 0) + (this.coordinationPrice || 0) + (this.eventStylingPrice || 0);
            },

            // Keep for any auto-resize you still want elsewhere
            autoResize(el) {
              el.style.height = 'auto';
              el.style.overflow = 'hidden';
              el.style.height = el.scrollHeight + 'px';
            },

            reflowAllTextareas() {
              this.$root.querySelectorAll('textarea[data-autoresize]').forEach(t => this.autoResize(t));
            },

            init() {
              this.$nextTick(() => {
                this.reflowAllTextareas();
                if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2));
              });
            }
          }));
        });
    </script>
</x-admin.layouts.management>
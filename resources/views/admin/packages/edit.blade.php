<x-admin.layouts.management>
    @php
    $config = [
    'initialInclusions' => old(
    'inclusions',
    $package->inclusions->map(fn ($i) => ['id' => $i->id])->values()
    ),
    'names' => $inclusions->pluck('name','id'),
    'categories' => $inclusions->pluck('category','id'),
    'prices' => $inclusions->pluck('price','id'),
    'notes' => $inclusions->pluck('notes','id'),
    'defaults' => [
    'coordinationPrice' => old('coordination_price', $package->coordination_price ?? 25000),
    'eventStylingPrice' => old('event_styling_price', $package->event_styling_price ?? 55000),
    'packagePrice' => old('price', $package->price ?? 0),
    'autoCalc' => (bool) old('autoCalc', true),
    'coordination' => old('coordination', $package->coordination ?? ''),
    'eventStylingText' => old('event_styling_text', is_array($package->event_styling) ? implode("\n",
    $package->event_styling) : ''),
    'isActive' => (bool) old('is_active', $package->is_active),
    ],
    ];
    @endphp

    <script type="application/json" id="pkg-config">
        {!! json_encode($config) !!}
    </script>

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6 max-w-3xl" x-data="packagePricing()" x-init="init()"
        x-cloak>
        <h3 class="text-lg font-semibold">Edit Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.update', $package) }}" class="space-y-6">
            @csrf
            @method('PUT')

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

            <style>
                [x-cloak] {
                    display: none !important
                }
            </style>

            <div class="space-y-4">
                <h4 class="font-semibold text-base">Inclusions</h4>

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
                        <div class="text-sm text-gray-700 shrink-0">₱{{ number_format($inc->price, 2) }}</div>
                    </label>
                    @endforeach
                </div>

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
                                            @click="remove(row.id)">Remove</button>
                                    </div>
                                </div>

                                <template x-if="notes[row.id]">
                                    <div
                                        class="mt-2 text-xs text-gray-700 whitespace-pre-line border rounded px-3 py-2 bg-white/60">
                                        <span x-text="notes[row.id]"></span>
                                    </div>
                                </template>

                                <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                            </div>
                        </template>

                        <div class="flex items-center justify-between mt-3 border-t pt-3">
                            <div class="text-sm text-gray-600">Inclusions Subtotal</div>
                            <div class="text-base font-semibold">₱<span x-text="fmt(subtotal())"></span></div>
                        </div>
                    </div>
                </template>

                <x-input-error :messages="$errors->get('inclusions')" />
                <x-input-error :messages="$errors->get('inclusions.*.id')" />
            </div>

            <div>
                <x-input-label>Coordination</x-input-label>
                <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2" x-model="coordination"
                    placeholder="e.g., Full coordination on the day; timeline and supplier follow-ups"></textarea>
                <x-input-error :messages="$errors->get('coordination')" />
            </div>

            <div>
                <x-input-label>Event Styling (one per line)</x-input-label>
                <textarea name="event_styling_text" rows="4" class="w-full border rounded px-3 py-2"
                    x-model="eventStylingText" placeholder="Stage setup
2-3 candles
Aisle decor"></textarea>
                <x-input-error :messages="$errors->get('event_styling_text')" />
            </div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Package Price" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                        x-model.number="packagePrice" x-bind:readonly="autoCalc" required />
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <input type="checkbox" class="rounded border-gray-300" id="autoCalc" x-model="autoCalc">
                        <label for="autoCalc">Auto-calc from inclusions + coordination + styling</label>
                    </div>
                    <input type="hidden" name="autoCalc" :value="autoCalc ? 1 : 0">
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                        :checked="isActive" @change="isActive = $event.target.checked" />
                    <x-input-label for="is_active" value="Active" />
                </div>
            </div>

            <div x-effect="if (autoCalc) { packagePrice = Number(grandTotal().toFixed(2)); }"></div>
            <div style="display:none"
                x-init="$watch(() => selected.map(r => r.id).join(','), () => { if (autoCalc) packagePrice = Number(grandTotal().toFixed(2)); })">
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Update Package</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('packagePricing', () => ({
                names: {},
                categories: {},
                prices: {},
                notes: {},
                selected: [],
                coordinationPrice: 0,
                eventStylingPrice: 0,
                packagePrice: 0,
                autoCalc: true,
                coordination: '',
                eventStylingText: '',
                isActive: true,

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
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },

                remove(id) {
                    id = Number(id);
                    const i = this.selected.findIndex(x => Number(x.id) === id);
                    if (i > -1) this.selected.splice(i, 1);
                    this.$nextTick(() => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
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

                init() {
                    const cfgEl = document.getElementById('pkg-config');
                    const cfg = cfgEl ? JSON.parse(cfgEl.textContent) : {};

                    this.names      = cfg.names      || {};
                    this.categories = cfg.categories || {};
                    this.prices     = cfg.prices     || {};
                    this.notes      = cfg.notes      || {};

                    const initSel = Array.isArray(cfg.initialInclusions) ? cfg.initialInclusions : [];
                    this.selected = initSel.map(v => (typeof v === 'object' && v !== null) ? { id: Number(v.id) } : { id: Number(v) });

                    const d = cfg.defaults || {};
                    this.coordinationPrice = Number(d.coordinationPrice ?? 25000);
                    this.eventStylingPrice = Number(d.eventStylingPrice ?? 55000);
                    this.packagePrice      = Number(d.packagePrice ?? 0);
                    this.autoCalc          = !!d.autoCalc;
                    this.coordination      = d.coordination ?? '';
                    this.eventStylingText  = d.eventStylingText ?? '';
                    this.isActive          = !!d.isActive;

                    this.$watch('coordinationPrice', () => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                    this.$watch('eventStylingPrice', () => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                    this.$watch('autoCalc', () => { if (this.autoCalc) this.packagePrice = Number(this.grandTotal().toFixed(2)); });
                },
            }));
        });
    </script>
</x-admin.layouts.management>
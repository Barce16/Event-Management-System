<x-admin.layouts.management>
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4 max-w-3xl">
        <h3 class="text-lg font-semibold">New Package</h3>

        <form method="POST" action="{{ route('admin.management.packages.store') }}" class="space-y-4">
            @csrf

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

            {{-- Inclusions --}}
            <style>
                [x-cloak] {
                    display: none !important
                }
            </style>

            <div x-data="inclusionsEditor({
                initial: @js(
                    old('inclusions',
                        ($package->inclusions ?? collect())
                            ->map(fn($i) => ['id' => $i->id, 'notes' => $i->pivot->notes])
                            ->values()
                    )
                ),

                names: @js($inclusions->pluck('name', 'id')),
                categories: @js($inclusions->pluck('category', 'id')),
            })" x-cloak class="mt-6 bg-white rounded-lg shadow-sm space-y-4">
                <h4 class="font-semibold text-base">Inclusions</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($inclusions as $inc)
                    <label class="flex items-center gap-2 border rounded px-3 py-2 hover:bg-gray-50">
                        <input type="checkbox" class="rounded border-gray-300" :checked="has({{ $inc->id }})"
                            @change="toggle({{ $inc->id }})">
                        <span class="font-medium">{{ $inc->name }}</span>
                        @if($inc->category)
                        <span class="text-xs text-gray-500">• {{ $inc->category }}</span>
                        @endif
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
                                    <button type="button" class="text-xs text-gray-500 hover:text-red-600 shrink-0"
                                        @click="remove(row.id)" aria-label="Remove inclusion">
                                        Remove
                                    </button>
                                </div>

                                <label class="sr-only" :for="`inc-notes-${row.id}`">Notes for inclusion</label>
                                <textarea :id="`inc-notes-${row.id}`"
                                    class="mt-2 w-full border rounded px-3 py-2 text-sm resize-none overflow-hidden focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                    :name="`inclusions[${idx}][notes]`" x-model.trim="row.notes" rows="1"
                                    x-init="autoResize($el)" @input="autoResize($event.target)"
                                    :placeholder="placeholder"></textarea>

                                {{-- Hidden ID field --}}
                                <input type="hidden" :name="`inclusions[${idx}][id]`" :value="row.id">
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Validation errors --}}
                <x-input-error :messages="$errors->get('inclusions')" />
                <x-input-error :messages="$errors->get('inclusions.*.id')" />
                <x-input-error :messages="$errors->get('inclusions.*.notes')" />
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                Alpine.data('inclusionsEditor', ({ initial = [], names = {}, categories = {} }) => ({
                selected: Array.isArray(initial) ? [...initial] : [],
                names,
                categories,
                placeholder: 'Notes (e.g., 30 sets, digital printing; 1-page invitation; free layout)',

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
                    this.selected.push({ id, notes: '' });
                    }
                    this.$nextTick(() => this.reflowAllTextareas());
                },

                remove(id) {
                    id = Number(id);
                    const i = this.selected.findIndex(x => Number(x.id) === id);
                    if (i > -1) this.selected.splice(i, 1);
                    this.$nextTick(() => this.reflowAllTextareas());
                },

                autoResize(el) {
                    el.style.height = 'auto';
                    el.style.overflow = 'hidden';
                    el.style.height = el.scrollHeight + 'px';
                },

                reflowAllTextareas() {
                    this.$root.querySelectorAll('textarea').forEach(t => this.autoResize(t));
                },
                }));
            });
            </script>

            {{-- Coordination --}}
            <div>
                <x-input-label>Coordination</x-input-label>
                <textarea name="coordination" rows="3" class="w-full border rounded px-3 py-2"
                    placeholder="e.g., Full coordination on the day; timeline and supplier follow-ups">{{ old('coordination') }}</textarea>
                <x-input-error :messages="$errors->get('coordination')" />
            </div>

            {{-- Event Styling --}}
            <div>
                <x-input-label>Event Styling (one per line)</x-input-label>
                <textarea name="event_styling_text" rows="4" class="w-full border rounded px-3 py-2"
                    placeholder="Stage setup&#10;2-3 candles&#10;Aisle decor">{{ old('event_styling_text') }}</textarea>
                <x-input-error :messages="$errors->get('event_styling_text')" />
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="price" value="Package Price" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                        value="{{ old('price', 0) }}" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300"
                        @checked(old('is_active', true)) />
                    <x-input-label for="is_active" value="Active" />
                </div>
            </div>

            <div>
                <x-input-label for="vendors" value="Include Vendors" />
                <select id="vendors" name="vendors[]" class="mt-1 w-full border rounded px-3 py-2" multiple size="8">
                    @foreach($vendors as $v)
                    <option value="{{ $v->id }}" @selected(collect(old('vendors', []))->contains($v->id))>
                        {{ $v->name }} @if($v->price) — ₱{{ number_format($v->price, 2) }} @endif
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple vendors.</p>
                <x-input-error :messages="$errors->get('vendors')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.management.packages.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Package</button>
            </div>
        </form>
    </div>
</x-admin.layouts.management>
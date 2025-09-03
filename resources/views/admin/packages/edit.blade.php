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
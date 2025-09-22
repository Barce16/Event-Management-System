<x-admin.layouts.management>
    <form method="POST"
        action="{{ isset($inclusion) ? route('admin.management.inclusions.update',$inclusion) : route('admin.management.inclusions.store') }}"
        class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        @isset($inclusion) @method('PUT') @endisset

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label>Name</x-input-label>
                <x-text-input name="name" class="w-full" value="{{ old('name', $inclusion->name ?? '') }}" />
                <x-input-error :messages="$errors->get('name')" />
            </div>
            <div>
                <x-input-label for="contact_person" value="Contact Person" />
                <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full"
                    value="{{ old('contact_person', $inclusion->contact_person ?? '') }}" />
            </div>

            <div>
                <x-input-label for="contact_email" value="Contact Email" />
                <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full"
                    value="{{ old('contact_email', $inclusion->contact_email ?? '') }}" />
            </div>

            <div>
                <x-input-label for="contact_phone" value="Contact Phone" />
                <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full"
                    value="{{ old('contact_phone', $inclusion->contact_phone ?? '') }}" />
            </div>

            <div>
                <x-input-label>Category (optional)</x-input-label>
                <x-text-input name="category" class="w-full"
                    value="{{ old('category', $inclusion->category ?? '') }}" />
            </div>
            <div>
                <x-input-label>Price</x-input-label>
                <x-text-input type="number" step="0.01" min="0" name="price" class="w-full"
                    value="{{ old('price', isset($inclusion) ? $inclusion->price : 0) }}" />
                <x-input-error :messages="$errors->get('price')" />
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $inclusion->is_active ??
                    true))
                    class="rounded border-gray-300">
                    <span>Active</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.management.inclusions.index') }}" class="px-3 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-gray-800 text-white rounded">{{ isset($inclusion)?'Save Changes':'Create
                Inclusion' }}</button>
        </div>
    </form>
</x-admin.layouts.management>
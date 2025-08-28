<x-admin.layouts.management>
    <h3 class="text-lg font-semibold mb-4">New Service</h3>

    <form method="POST" action="{{ route('admin.management.services.store') }}" class="space-y-4 max-w-xl">
        @csrf
        <div>
            <x-input-label for="name" value="Service Name" />
            <x-text-input id="name" name="name" class="mt-1 w-full" value="{{ old('name') }}" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3"
                class="mt-1 w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="base_price" value="Base Price (₱)" />
            <x-text-input id="base_price" name="base_price" type="number" step="0.01" min="0" class="mt-1 w-full"
                value="{{ old('base_price', 0) }}" required />
            <x-input-error :messages="$errors->get('base_price')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" checked class="border rounded">
            <label for="is_active">Active</label>
        </div>

        <div class="pt-2">
            <x-primary-button>Save</x-primary-button>
            <a href="{{ route('admin.management.services.index') }}"
                class="ml-2 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</x-admin.layouts.management>
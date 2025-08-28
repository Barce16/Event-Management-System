<x-admin.layouts.management>
    <h3 class="text-lg font-semibold mb-4">New Event Type</h3>

    <form method="POST" action="{{ route('admin.management.event-types.store') }}" class="space-y-4 max-w-xl">
        @csrf

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" class="mt-1 w-full" required />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3"
                class="mt-1 w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded border-gray-300">
            <label for="is_active">Active</label>
        </div>

        <div class="pt-2">
            <x-primary-button>Save</x-primary-button>
            <a href="{{ route('admin.management.event-types.index') }}"
                class="ml-2 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</x-admin.layouts.management>
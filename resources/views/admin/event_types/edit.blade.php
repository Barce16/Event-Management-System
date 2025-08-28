<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Event Type</h2>
            <a href="{{ route('admin.management.event-types.index') }}" class="underline">Back</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.management.event-types.update', $event_type) }}"
                    class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" class="mt-1 w-full"
                            value="{{ old('name',$event_type->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 w-full border rounded px-3 py-2">{{ old('description',$event_type->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active',$event_type->is_active)
                            ? 'checked' : '' }} class="rounded border-gray-300">
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <x-primary-button>Save changes</x-primary-button>
                        <a href="{{ route('admin.management.event-types.index') }}"
                            class="ml-2 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
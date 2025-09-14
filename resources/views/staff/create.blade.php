<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">New Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Optional: error summary --}}
            @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <div class="font-semibold mb-1">Please fix the following:</div>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('staff.store') }}" class="bg-white p-6 rounded shadow space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label>Name</x-input-label>
                        <x-text-input name="name" value="{{ old('name') }}" class="w-full" autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label>Email</x-input-label>
                        <x-text-input type="email" name="email" value="{{ old('email') }}" class="w-full"
                            autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="username">Username</x-input-label>
                        <x-text-input id="username" name="username" value="{{ old('username') }}" class="w-full"
                            required autocomplete="username" />
                        <x-input-error :messages="$errors->get('username')" />
                    </div>

                    <div>
                        <x-input-label for="password">Password</x-input-label>
                        <x-text-input id="password" type="password" name="password" class="w-full" required
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>


                    <div>
                        <x-input-label>Contact Number</x-input-label>
                        <x-text-input name="contact_number" value="{{ old('contact_number') }}" class="w-full"
                            autocomplete="tel" />
                        <x-input-error :messages="$errors->get('contact_number')" />
                    </div>

                    <div>
                        <x-input-label>Role Type</x-input-label>
                        <x-text-input name="role_type" value="{{ old('role_type') }}" class="w-full" />
                        <x-input-error :messages="$errors->get('role_type')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label>Address</x-input-label>
                        <x-text-input name="address" value="{{ old('address') }}" class="w-full"
                            autocomplete="street-address" />
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    <div>
                        <x-input-label>Gender</x-input-label>
                        <select name="gender" class="border rounded px-3 py-2 w-full">
                            <option value="">—</option>
                            @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" @selected(old('gender')===$g)>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('gender')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label>Remarks</x-input-label>
                        <textarea name="remarks" rows="3"
                            class="w-full border rounded px-3 py-2">{{ old('remarks') }}</textarea>
                        <x-input-error :messages="$errors->get('remarks')" />
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                                class="rounded border-gray-300">
                            <span>Active</span>
                        </label>
                        <x-input-error :messages="$errors->get('is_active')" />
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('staff.index') }}" class="px-3 py-2 border rounded">Cancel</a>
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
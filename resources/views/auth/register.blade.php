<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-input-label for="name" :value="__('Full Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />

        <x-input-label for="username" :value="__('Username')" class="mt-4" />
        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" value="{{ old('username') }}"
            required />
        <x-input-error :messages="$errors->get('username')" class="mt-2" />

        <x-input-label for="email" :value="__('Email')" class="mt-4" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}"
            required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <x-input-label for="phone" :value="__('Phone')" class="mt-4" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />

        <x-input-label for="address" :value="__('Address')" class="mt-4" />
        <textarea id="address" name="address" rows="2"
            class="mt-1 w-full border rounded px-3 py-2">{{ old('address') }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />

        <x-input-label for="password" :value="__('Password')" class="mt-4" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />

        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="mt-4" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full"
            required />

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ url('/') }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Cancel
            </a>
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
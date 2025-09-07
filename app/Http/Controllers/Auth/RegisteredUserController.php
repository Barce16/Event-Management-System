<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
                'regex:/^[^<>=\'"]*$/',
            ],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone'     => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]*$/',
            ],
            'address'   => ['nullable', 'string', 'max:255'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($data) {
            // 1) Create auth account
            $user = User::create([
                'name'      => $data['name'],
                'username'  => $data['username'],
                'email'     => $data['email'],
                'user_type' => 'customer',
                'status'    => 'active',
                'password'  => Hash::make($data['password']),
            ]);

            // 2) Create customer profile linked to that user
            Customer::create([
                'user_id'       => $user->id,
                'customer_name' => $data['name'],
                'email'         => $data['email'],
                'phone'         => $data['phone'] ?? null,
                'address'       => $data['address'] ?? null,
            ]);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Welcome! Your customer account is ready.');
    }
}

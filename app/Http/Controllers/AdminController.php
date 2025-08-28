<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function createUserForm()
    {
        return view('admin.create-user');
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:50', 'unique:users,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'user_type' => ['required', Rule::in(['admin', 'staff'])],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'username'  => $data['username'],
            'email'     => $data['email'],
            'user_type' => $data['user_type'],
            'password'  => bcrypt($data['password']),
        ]);

        return redirect()->route('admin.users.list')
            ->with('success', 'User created successfully.');
    }

    public function listUsers()
    {
        $users = User::select('id', 'name', 'email', 'user_type', 'created_at', 'status')
            ->latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function block(User $user)
    {
        if ($user->user_type === 'admin') {
            return back()->with('error', 'You cannot block an admin.');
        }

        $user->update(['status' => 'blocked']);
        return back()->with('success', 'User has been blocked.');
    }

    public function unblock(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User has been unblocked.');
    }

    public function managementIndex()
    {
        return view('admin.management.index');
    }
}

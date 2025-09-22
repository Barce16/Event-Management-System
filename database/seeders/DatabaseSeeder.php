<?php

namespace Database\Seeders;

use App\Models\Inclusion;
use App\Models\User;
use App\Models\Package;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $upsertUser = function (array $attrs): User {
            $email = $attrs['email'] ?? null;
            $username = $attrs['username'] ?? null;

            $user = User::query()
                ->when($email, fn($q) => $q->orWhere('email', $email))
                ->when($username, fn($q) => $q->orWhere('username', $username))
                ->first();

            if (empty($attrs['password'])) {
                $attrs['password'] = Hash::make('password');
            }

            if ($user) {
                $user->fill([
                    'name'      => $attrs['name']      ?? $user->name,
                    'username'  => $username           ?? $user->username,
                    'email'     => $email              ?? $user->email,
                    'user_type' => $attrs['user_type'] ?? $user->user_type,
                ]);
                if (array_key_exists('password', $attrs)) {
                    $user->password = $attrs['password'];
                }
                $user->save();
                return $user;
            }

            // Create new
            return User::create($attrs);
        };

        // ---- USERS ----
        $upsertUser([
            'name'      => 'Test User',
            'username'  => 'testuser',
            'email'     => 'test@example.com',
            'user_type' => 'customer',
            'password'  => Hash::make('password'),
        ]);

        $upsertUser([
            'name'      => 'Admin',
            'username'  => 'admin',
            'email'     => 'admin@example.com',
            'user_type' => 'admin',
            'password'  => Hash::make('password'),
        ]);

        // ---- VENDORS ----
        $vendors = [
            ['name' => 'Premium Catering',           'category' => 'Catering',        'price' => 50000],
            ['name' => 'Everlight Sounds & Lights',  'category' => 'Lights & Sounds', 'price' => 15000],
            ['name' => 'SnapShot Photo & Video',     'category' => 'Photo/Video',     'price' => 20000],
            ['name' => 'Blossom Florals',            'category' => 'Florist',         'price' => 8000],
        ];
        foreach ($vendors as $v) {
            Vendor::firstOrCreate(['name' => $v['name']], $v);
        }

        // Fetch vendor IDs safely
        $catering = Vendor::where('category', 'Catering')->value('id');
        $lights   = Vendor::where('category', 'Lights & Sounds')->value('id');
        $photo    = Vendor::where('category', 'Photo/Video')->value('id');
        $florist  = Vendor::where('category', 'Florist')->value('id');

        // ---- PACKAGES ----
        $basic = Package::firstOrCreate(
            ['name' => 'Basic Wedding'],
            [
                // adjust to your column names, e.g. base_price vs price
                'slug'        => Str::slug('Basic Wedding'),
                'base_price'  => 50000,
                'description' => 'Core vendors included',
                'is_active'   => true,
            ]
        );

        $premium = Package::firstOrCreate(
            ['name' => 'Premium Wedding'],
            [
                'slug'        => Str::slug('Premium Wedding'),
                'base_price'  => 120000,
                'description' => 'Premium lineup',
                'is_active'   => true,
            ]
        );

        // Sync vendors to packages
        $basic->vendors()->sync(array_values(array_filter([$catering, $photo, $florist])));
        $basic->update([
            'event_styling' => ['Stage setup', '2-3 candles', 'Aisle decor'],
            'coordination'  => 'Day-of coordination, supplier follow-ups',
        ]);

        $premium->vendors()->sync(array_values(array_filter([$catering, $photo, $florist, $lights])));

        // ---- INCLUSIONS ----
        foreach (['Invitations', 'Giveaways', 'Photos', 'Videos', 'Cake'] as $name) {
            Inclusion::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}

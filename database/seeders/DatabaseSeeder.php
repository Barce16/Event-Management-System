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
        $basic->update([
            'event_styling' => ['Stage setup', '2-3 candles', 'Aisle decor'],
            'coordination'  => 'Day-of coordination, supplier follow-ups',
        ]);

        $premium->vendors()->sync(array_values(array_filter([$catering, $photo, $florist, $lights])));

        // ---- INCLUSIONS ----
        // Insert the inclusions directly as per the SQL provided
        Inclusion::insert([
            [
                'id' => 1,
                'name' => 'Invitations',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 9600.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "30sets Digital Printing\r\n3 pages; 2 regular sized card, 1 small card\r\nFREE LAY-OUT",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'Giveaways',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 22000.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "30 pcs.\r\nWith tags/labels\r\nChoices of: Honey Jars, Coffee Bean Jars, Succulents\r\nTablea Pouch",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'Photos',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 46500.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\nOn-the-Day Coverage\r\nAVP Prenup and SDE\r\n50pcs 5r Prints\r\nUSB Softcopy of Photos\r\n2-4 Photographers",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'Videos',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 62000.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\nHighlights of the Event\r\nAVP Prenup and SDE\r\n2-4 Videographers",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'name' => 'Cake',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 13100.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "3-tier\r\nDimension:\r\nChoices of Butter and/or Chocolate",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'name' => 'HMUA',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 15000.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\n10 Heads On-the-Day of the Event (including bride)",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'name' => 'Host',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 12200.00,
                'category' => null,
                'is_active' => 1,
                'notes' => "with musical scorer",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}

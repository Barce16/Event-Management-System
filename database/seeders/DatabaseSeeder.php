<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'user_type' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // -- VENDORS SEEDER

        $rows = [
            ['name' => 'Premium Catering', 'category' => 'Catering'],
            ['name' => 'Everlight Sounds & Lights', 'category' => 'Lights & Sounds'],
            ['name' => 'SnapShot Photo & Video', 'category' => 'Photo/Video'],
            ['name' => 'Blossom Florals', 'category' => 'Florist'],
        ];
        foreach ($rows as $r) Vendor::firstOrCreate(['name' => $r['name']], $r);

        // -- PACKAGES SEEDER
        $basic = Package::firstOrCreate(
            ['name' => 'Basic Wedding'],
            ['slug' => Str::slug('Basic Wedding'), 'base_price' => 50000, 'description' => 'Core vendors included']
        );
        $premium = Package::firstOrCreate(
            ['name' => 'Premium Wedding'],
            ['slug' => Str::slug('Premium Wedding'), 'base_price' => 120000, 'description' => 'Premium lineup']
        );

        $catering = Vendor::where('category', 'Catering')->first();
        $lights   = Vendor::where('category', 'Lights & Sounds')->first();
        $photo    = Vendor::where('category', 'Photo/Video')->first();
        $florist  = Vendor::where('category', 'Florist')->first();

        $basic->vendors()->sync([$catering?->id, $photo?->id, $florist?->id]);
        $premium->vendors()->sync([$catering?->id, $photo?->id, $florist?->id, $lights?->id]);
    }
}

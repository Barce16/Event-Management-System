<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Premium Catering', 'category' => 'Catering'],
            ['name' => 'Everlight Sounds & Lights', 'category' => 'Lights & Sounds'],
            ['name' => 'SnapShot Photo & Video', 'category' => 'Photo/Video'],
            ['name' => 'Blossom Florals', 'category' => 'Florist'],
        ];
        foreach ($rows as $r) Vendor::firstOrCreate(['name' => $r['name']], $r);
    }
}

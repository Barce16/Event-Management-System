<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::create([
            'customer_name' => 'Juan Dela Cruz',
            'email'         => 'juan@example.com',
            'phone'         => '09171234567',
            'address'       => 'Quezon City'
        ]);
    }
}

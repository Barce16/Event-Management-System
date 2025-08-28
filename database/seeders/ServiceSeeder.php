<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Catering',                   'desc' => 'Buffet or plated options'],
            ['name' => 'Venue Decoration',          'desc' => 'Styling, florals, backdrops'],
            ['name' => 'Photography',               'desc' => 'Professional coverage, edits'],
            ['name' => 'Videography',               'desc' => 'Full event film & highlights'],
            ['name' => 'Sound & Lights',            'desc' => 'PA system, lighting rigs'],
            ['name' => 'Entertainment',             'desc' => 'Band, DJ, performers'],
            ['name' => 'Security',                  'desc' => 'Guards & crowd control'],
            ['name' => 'Transportation',            'desc' => 'Shuttles, VIP cars'],
            ['name' => 'Invitations & Stationery',  'desc' => 'Design & print'],
            ['name' => 'Cake & Desserts',           'desc' => 'Custom cakes, dessert bar'],
            ['name' => 'Hair & Makeup',             'desc' => 'HMUA team'],
            ['name' => 'Emcee/Host',                'desc' => 'Professional host'],
            ['name' => 'Event Coordination',        'desc' => 'Partial/Full planning'],
        ];

        foreach ($items as $i) {
            Service::firstOrCreate(
                ['name' => $i['name']],
                ['description' => $i['desc'], 'base_price' => 0, 'is_active' => true]
            );
        }
    }
}

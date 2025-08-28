<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Wedding',               'description' => 'Ceremony + reception planning'],
            ['name' => 'Birthday Party',        'description' => 'All ages birthday celebrations'],
            ['name' => 'Debut (18th)',          'description' => 'Traditional debut celebration'],
            ['name' => 'Kiddie Party',          'description' => 'Kids birthday or themed parties'],
            ['name' => 'Corporate Event',       'description' => 'Company internal/external events'],
            ['name' => 'Conference',            'description' => 'Multi-track conference setup'],
            ['name' => 'Seminar',               'description' => 'Talks, trainings, short programs'],
            ['name' => 'Workshop',              'description' => 'Hands-on/interactive sessions'],
            ['name' => 'Product Launch',        'description' => 'Launches, press & promos'],
            ['name' => 'Gala / Dinner',         'description' => 'Formal banquets and galas'],
            ['name' => 'Fundraiser / Charity',  'description' => 'Benefit and charity events'],
            ['name' => 'Anniversary',           'description' => 'Milestone celebrations'],
            ['name' => 'Engagement Party',      'description' => 'Pre-wedding celebration'],
            ['name' => 'Bridal Shower',         'description' => 'Party for the bride-to-be'],
            ['name' => 'Baby Shower',           'description' => 'Celebrating upcoming baby'],
            ['name' => 'Graduation',            'description' => 'Grad balls & ceremonies'],
            ['name' => 'Reunion',               'description' => 'Family/school/company reunions'],
            ['name' => 'Award Ceremony',        'description' => 'Recognition & awards nights'],
            ['name' => 'Holiday Party',         'description' => 'Christmas/New Year parties'],
            ['name' => 'Photoshoot',            'description' => 'Styled or production shoots'],
        ];

        $now = Carbon::now();

        $rows = collect($types)->map(function ($t) use ($now) {
            return [
                'name'        => $t['name'],
                'slug'        => Str::slug($t['name']),
                'description' => $t['description'],
                'is_active'   => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        })->all();

        DB::table('event_types')->upsert(
            $rows,
            ['name'],
            ['slug', 'description', 'is_active', 'updated_at']
        );
    }
}

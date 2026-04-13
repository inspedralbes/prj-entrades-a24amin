<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventZone;
use App\Models\Seat;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::create([
            'name' => 'Final UEFA Champions League 2026',
            'event_date' => '2026-05-30 21:00:00',
            'location' => 'Estadi Olímpic Lluís Companys, Barcelona',
            'description' => 'La gran final de la competició més prestigiosa de clubs d\'Europa.',
            'capacity' => 50,
        ]);

        $zones = [
            ['name' => 'Tribuna VIP', 'price' => 550.00, 'rows' => 2, 'cols' => 5],
            ['name' => 'Preferent', 'price' => 250.00, 'rows' => 4, 'cols' => 10],
        ];

        foreach ($zones as $zData) {
            $zone = EventZone::create([
                'event_id' => $event->id,
                'name' => $zData['name'],
                'price' => $zData['price'],
            ]);

            for ($r = 1; $r <= $zData['rows']; $r++) {
                for ($c = 1; $c <= $zData['cols']; $c++) {
                    Seat::create([
                        'event_zone_id' => $zone->id,
                        'identifier' => "R{$r}-C{$c}",
                        'status' => 'available',
                    ]);
                }
            }
        }
    }
}
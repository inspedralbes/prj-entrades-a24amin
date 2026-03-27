<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventZone;
use App\Models\Seat;

class EventsSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(base_path('../database/events.json'));
        $data = json_decode($json, true);

        foreach ($data as $e) {
            $event = Event::create([
                'name' => $e['name'],
                'description' => $e['description'],
                'location' => $e['location'],
                'event_date' => $e['event_date'],
                'image_url' => $e['image_url'],
            ]);

            foreach ($e['zones'] as $z) {
                $zone = EventZone::create([
                    'event_id' => $event->id,
                    'name' => $z['name'],
                    'price' => $z['price'],
                ]);

                for ($r = 1; $r <= $z['rows']; $r++) {
                    for ($c = 1; $c <= $z['cols']; $c++) {
                        Seat::create([
                            'event_zone_id' => $zone->id,
                            'row' => $r,
                            'col' => $c,
                            'status' => 'available',
                        ]);
                    }
                }
            }
        }
    }
}
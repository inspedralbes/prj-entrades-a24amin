<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventZone;
use App\Models\Seat;
use Illuminate\Support\Str;

class MassiveEventSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Seat::truncate();
        \App\Models\EventZone::truncate();
        \App\Models\Event::truncate();
        \App\Models\Order::truncate();
        \App\Models\Ticket::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $genres = ['Acció', 'Drama', 'Comèdia', 'Terror', 'Ciència Ficció', 'Animació', 'Documental', 'Thriller', 'Romàntica', 'Musical'];
        $directors = ['Christopher Nolan', 'Steven Spielberg', 'Quentin Tarantino', 'Martin Scorsese', 'Greta Gerwig', 'James Cameron', 'Wes Anderson', 'Denis Villeneuve', 'Pedro Almodóvar', 'Guillermo del Toro'];
        $locations = ['Sala Premium 1', 'Sala Premium 2', 'Sala IMAX', 'Sala 4DX', 'Sala Dolby Cinema', 'Sala VIP Gold'];

        $movieTitles = [
            'Oppenheimer', 'Barbie', 'Dune: Part Two', 'Poor Things', 'The Holdovers', 'Napoleon', 'Killers of the Flower Moon', 'Past Lives', 'Anatomy of a Fall', 'The Zone of Interest',
            'Spider-Man: Across the Spider-Verse', 'Elemental', 'The Super Mario Bros. Movie', 'Migration', 'Wish', 'Trolls Band Together', 'Kung Fu Panda 4', 'Inside Out 2', 'Despicable Me 4', 'Moana 2',
            'Mission: Impossible - Dead Reckoning', 'John Wick: Chapter 4', 'Fast X', 'Guardians of the Galaxy Vol. 3', 'Indiana Jones 5', 'The Flash', 'Aquaman 2', 'Blue Beetle', 'The Marvels', 'Godzilla x Kong',
            'Late Night with the Devil', 'When Evil Lurks', 'Talk to Me', 'The First Omen', 'Civil War', 'The Creator', 'M3GAN', 'Smile', 'Barbarian', 'Pearl',
            'Everything Everywhere All At Once', 'Top Gun: Maverick', 'Avatar: The Way of Water', 'The Batman', 'The Whale', 'Tar', 'The Banshees of Inisherin', 'Triangle of Sadness', 'Elvis', 'Babylon',
            'Society of the Snow', 'Robot Dreams', 'Robot Dreams 2', 'Close', 'Decision to Leave', 'Holy Spider', 'The Quiet Girl', 'Alcarràs', 'Eo', 'The Worst Person in the World',
            'Parasite', 'Joker', '1917', 'Marriage Story', 'The Irishman', 'Once Upon a Time in Hollywood', 'Jojo Rabbit', 'Little Women', 'Ford v Ferrari', 'Knives Out',
            'The Green Knight', 'Pig', 'Titane', 'Drives My Car', 'Licorice Pizza', 'Cmon Cmon', 'Belfast', 'The Power of the Dog', 'Nightmare Alley', 'Spencer',
            'Mad Max: Fury Road', 'The Revenant', 'Arrival', 'La La Land', 'Moonlight', 'Manchester by the Sea', 'Hidden Figures', 'Lion', 'Fences', 'Hell or High Water',
            'The Grand Budapest Hotel', 'Boyhood', 'Birdman', 'Whiplash', 'The Imitation Game', 'Selma', 'American Sniper', 'The Theory of Everything', 'Interstellar', 'Gone Girl'
        ];

        $movieTitles = array_slice($movieTitles, 0, 30);

        foreach ($movieTitles as $index => $title) {
            $event = Event::create([
                'name' => $title,
                'director' => $directors[array_rand($directors)],
                'genre' => $genres[array_rand($genres)],
                'description' => "Una experiència cinematogràfica única centrada en " . strtolower($title) . ". Direcció magistral i una història que et captivarà des del primer minut.",
                'location' => $locations[array_rand($locations)],
                'event_date' => now()->addDays(rand(1, 45))->setHour(rand(16, 22))->setMinute(0),
                'image_url' => "https://picsum.photos/seed/" . Str::slug($title) . "/800/1200",
            ]);

            // Creem 2 zones per pel·lícula
            $zoneTypes = [
                ['name' => 'Butaques Estàndard', 'price' => 9.50, 'rows' => 5, 'cols' => 8],
                ['name' => 'Butaques VIP Shôko', 'price' => 14.50, 'rows' => 3, 'cols' => 6]
            ];

            foreach ($zoneTypes as $z) {
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

            // Simulem algunes vendes aleatòries per als gràfics (només per a les primeres 10 pelis)
            if ($index < 10) {
                $numOrders = rand(5, 15);
                $user = \App\Models\User::first() ?: \App\Models\User::create([
                    'name' => 'Amin',
                    'email' => 'amin@cinemashoko.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                ]);

                for ($i = 0; $i < $numOrders; $i++) {
                    $orderDate = now()->subDays(rand(0, 15));
                    $order = \App\Models\Order::create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'total_price' => rand(20, 100),
                        'order_number' => 'REC-' . strtoupper(Str::random(10)),
                        'status' => 'paid',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }
            }
        }
    }
}
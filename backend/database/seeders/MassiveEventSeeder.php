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

        $movieTitles = array_slice([
            'Oppenheimer', 'Barbie', 'Dune: Part Two', 'Poor Things', 'The Holdovers', 'Napoleon', 'Killers of the Flower Moon', 'Past Lives', 'Anatomy of a Fall', 'The Zone of Interest',
            'Spider-Man: Across the Spider-Verse', 'Elemental', 'The Super Mario Bros. Movie', 'Migration', 'Wish', 'Trolls Band Together', 'Kung Fu Panda 4', 'Inside Out 2', 'Despicable Me 4', 'Moana 2',
            'Mission: Impossible - Dead Reckoning', 'John Wick: Chapter 4', 'Fast X', 'Guardians of the Galaxy Vol. 3', 'Indiana Jones 5', 'The Flash', 'Aquaman 2', 'Blue Beetle', 'The Marvels', 'Godzilla x Kong'
        ], 0, 30);

        $premiumPosters = [
            'Oppenheimer' => 'https://m.media-amazon.com/images/M/MV5BNTFlZDI1YWQtMTVjNy00YWU1LTg2YjktMTlhYmRiYzQ3NTVhXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
            'Barbie' => 'https://decine21.com/img/upload/obras/barbie-45463/barbie-45463-c.jpg',
            'Dune: Part Two' => 'https://pics.filmaffinity.com/Dune_Parte_Dos-288678616-large.jpg',
            'Poor Things' => 'https://m.media-amazon.com/images/I/81xmFznkhWL.jpg',
            'The Holdovers' => 'https://m.media-amazon.com/images/I/71nvw+xcwdL._AC_UF894,1000_QL80_.jpg',
            'Napoleon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQJiZUZ_2HPc-cOYNqsD-2Z7UnBVIsjxUNxQ&s',
            'Killers of the Flower Moon' => 'https://m.media-amazon.com/images/M/MV5BNzE5MDY0ZDUtYjg2NS00ZjRkLWJkZjEtNGYyOWNmZDk0MDhmXkEyXkFqcGc@._V1_.jpg',
            'Past Lives' => 'https://m.media-amazon.com/images/I/81iJAtNLX3L._AC_UF894,1000_QL80_.jpg',
            'Anatomy of a Fall' => 'https://m.media-amazon.com/images/M/MV5BYTk3MjFkZmQtMThiYi00N2JhLTk2YzItZTE5ZGUxZTIyZDVkXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
            'The Zone of Interest' => 'https://www.cineciutat.org/storage/app/uploads/public/660/546/55d/thumb_4171_314_443_0_0_crop.jpg',
            'Spider-Man: Across the Spider-Verse' => 'https://m.media-amazon.com/images/M/MV5BMjZmZTZmMjgtMzNjYS00MDE5LTljODAtZTg4YTg5NWI3MjMxXkEyXkFqcGc@._V1_.jpg',
            'Elemental' => 'https://pics.filmaffinity.com/Elemental-522221727-large.jpg',
            'The Super Mario Bros. Movie' => 'https://m.media-amazon.com/images/I/81vwmHIJgFL._AC_UF894,1000_QL80_.jpg',
            'Migration' => 'https://m.media-amazon.com/images/M/MV5BNjYwNjhhN2UtYTM3My00Yzk3LWIwMTMtNmE4ZWQ1ZTVjYzQwXkEyXkFqcGc@._V1_.jpg',
            'Wish' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYnrEoqwOSYYUK2eE9rXLHa6aDUiWEwq9pqA&s',
            'Trolls Band Together' => 'https://m.media-amazon.com/images/M/MV5BY2EzNTY0N2MtMTAyZS00ZTIwLTk5MmUtNWJhMmIwYmM5OWFiXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
            'Kung Fu Panda 4' => 'https://pics.filmaffinity.com/Kung_Fu_Panda_4-675656087-large.jpg',
            'Inside Out 2' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCxxzDI3I5TMHKpJrLJR_G25vS_1GHbrWh9A&s',
            'Despicable Me 4' => 'https://m.media-amazon.com/images/M/MV5BYjUwODkxNmEtMWVhNS00ZGVkLTkyZDMtNmM1OTQ3NjcxZDc0XkEyXkFqcGc@._V1_.jpg',
            'Moana 2' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRE96-ST9y3L5F18IFNnTPV3aEyUAEvxpUIUA&s',
            'Mission: Impossible - Dead Reckoning' => 'https://m.media-amazon.com/images/I/818iKQRx4XL._AC_UF894,1000_QL80_.jpg',
            'John Wick: Chapter 4' => 'https://m.media-amazon.com/images/I/81fk-N7tvbL._AC_UF894,1000_QL80_.jpg',
            'Fast X' => 'https://m.media-amazon.com/images/I/81x8lr2PNHL._AC_UF894,1000_QL80_.jpg',
            'Guardians of the Galaxy Vol. 3' => 'https://m.media-amazon.com/images/M/MV5BOTJhOTMxMmItZmE0Ny00MDc3LWEzOGEtOGFkMzY4MWYyZDQ0XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
            'Indiana Jones 5' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTADaGmPaSM3TtyNPXXqBXVT3rS498ubBdUKw&s',
            'The Flash' => 'https://m.media-amazon.com/images/M/MV5BYTY0YjQ5Y2EtNDJiYi00ZjMwLWFmMTYtNzc2YzZkZWQzZjU4XkEyXkFqcGc@._V1_.jpg',
            'Aquaman 2' => 'https://pics.filmaffinity.com/Aquaman-355320736-large.jpg',
            'Blue Beetle' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS22jvQj5erU-jEey5GoiltcVWYWIglnBN2oQ&s',
            'The Marvels' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQlB_NvnuzdsZ0GxnDMAU_VEYJEnyxeh-fdiw&s',
            'Godzilla x Kong' => 'https://m.media-amazon.com/images/M/MV5BMTY0N2MzODctY2ExYy00OWYxLTkyNDItMTVhZGIxZjliZjU5XkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg',
        ];

        foreach ($movieTitles as $index => $title) {
            $imageUrl = $premiumPosters[$title] ?? "https://picsum.photos/seed/" . Str::slug($title) . "/800/1200";

            $event = Event::create([
                'name' => $title,
                'director' => $directors[array_rand($directors)],
                'genre' => $genres[array_rand($genres)],
                'description' => "Una experiència cinematogràfica única centrada en " . strtolower($title) . ". Direcció magistral i una història que et captivarà des del primer minut.",
                'location' => $locations[array_rand($locations)],
                'event_date' => now()->addDays(rand(1, 45))->setHour(rand(16, 22))->setMinute(0),
                'image_url' => $imageUrl,
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
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of public events.
     */
    public function index()
    {
        return response()->json(Event::all());
    }

    /**
     * Display the specified event with its zones and seats.
     */
    public function show(string $id)
    {
        $event = Event::with(['zones.seats'])->findOrFail($id);

        // Mergem l'estat de Redis i autosanem seients orfes (si estan occupied però no tenen tiquet)
        foreach ($event->zones as $zone) {
            foreach ($zone->seats as $seat) {
                if ($seat->status === 'available') {
                    if (\Illuminate\Support\Facades\Redis::exists("seat_reservation:{$seat->id}")) {
                        $seat->status = 'reserved';
                    }
                }
                elseif ($seat->status === 'occupied') {
                    // Autosanació: si està occupied però no hi ha tiquet, el tornem a alliberar
                    if ($seat->tickets()->count() === 0) {
                        $seat->update(['status' => 'available']);
                        $seat->status = 'available';
                    }
                }
            }
        }

        return response()->json($event);
    }
}
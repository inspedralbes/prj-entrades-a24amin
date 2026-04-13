<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    public function index()
    {
        return Event::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return Event::create($validated);
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'event_date' => 'date',
            'location' => 'string',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);
        return $event;
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Esdeveniment eliminat']);
    }

    /**
     * Obtenir estadístiques en temps real de l'esdeveniment.
     */
    public function stats(string $id)
    {
        $event = Event::with('zones.seats')->findOrFail($id);

        $totalSeats = 0;
        $occupiedSeats = 0;
        $reservedSeats = 0;
        $totalRevenue = 0;

        foreach ($event->zones as $zone) {
            foreach ($zone->seats as $seat) {
                $totalSeats++;

                if ($seat->status === 'occupied') {
                    $occupiedSeats++;
                    $totalRevenue += $zone->price;
                }
                else {
                    // Verifiquem si està reservat a Redis
                    if (Redis::exists("seat_reservation:{$seat->id}")) {
                        $reservedSeats++;
                    }
                }
            }
        }

        return response()->json([
            'total_seats' => $totalSeats,
            'occupied_seats' => $occupiedSeats,
            'reserved_seats' => $reservedSeats,
            'available_seats' => $totalSeats - $occupiedSeats - $reservedSeats,
            'total_revenue' => $totalRevenue,
            'occupancy_percentage' => $totalSeats > 0 ? round(($occupiedSeats / $totalSeats) * 100, 2) : 0
        ]);
    }

    /**
     * Obtenir estadístiques globals del cinema (ingressos per dia).
     */
    public function globalStats()
    {
        $salesByDate = \App\Models\Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('status', 'paid')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json([
            'sales_evolution' => $salesByDate
        ]);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Reclama un seient temporalment (10 minuts).
     */
    public function reserve(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $userId = Auth::id();
        $seatId = $request->seat_id;
        $eventId = $request->event_id;
        $redisKey = "seat_reservation:{$seatId}";

        // 1. Comprovar si el seient ja està ocupat a la BD (compra finalitzada)
        $seat = Seat::findOrFail($seatId);
        if ($seat->status === 'occupied') {
            return response()->json(['message' => 'El seient ja està venut.'], 422);
        }

        // 2. Intentar bloquejar a Redis (bloqueig atòmic de 10 minuts)
        // NX: Només si no existeix, EX: Expira en X segons
        $reserved = Redis::set($redisKey, $userId, 'EX', 600, 'NX');

        if (!$reserved) {
            return response()->json(['message' => 'Aquest seient ja està reservat per un altre usuari.'], 422);
        }

        // 3. Notificar via Redis Pub/Sub (perquè el WebSocket ho emeti a tothom)
        Redis::publish('seat-updates', json_encode([
            'event_id' => $eventId,
            'seat_id' => $seatId,
            'status' => 'reserved',
            'user_id' => $userId
        ]));

        return response()->json([
            'message' => 'Seient reservat temporalment.',
            'expires_in' => 600
        ]);
    }

    /**
     * Allibera un seient reservat anteriorment pel propi usuari.
     */
    public function release(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
            'event_id' => 'required|exists:events,id',
        ]);

        $userId = Auth::id();
        $seatId = $request->seat_id;
        $eventId = $request->event_id;
        $redisKey = "seat_reservation:{$seatId}";

        // Només l'usuari que ha reservat pot alliberar-lo manualment
        $reserver = Redis::get($redisKey);
        if ($reserver == $userId) {
            Redis::del($redisKey);

            // Notificar que ara està lliure
            Redis::publish('seat-updates', json_encode([
                'event_id' => $eventId,
                'seat_id' => $seatId,
                'status' => 'available'
            ]));

            return response()->json(['message' => 'Seient alliberat.']);
        }

        return response()->json(['message' => 'No tens permís per alliberar aquest seient.'], 403);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;

class OrderController extends Controller
{
    /**
     * Finalitza una compra convertint les reserves de Redis en una ordre persistent.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
        ]);

        $userId = Auth::id();
        $seatIds = $request->seats;
        $eventId = $request->event_id;

        // Utilitzem una transacció de BD per assegurar la integritat
        return DB::transaction(function () use ($userId, $seatIds, $eventId) {
            $totalPrice = 0;
            $seatsToProcess = [];

            foreach ($seatIds as $seatId) {
                $redisKey = "seat_reservation:{$seatId}";
                $reserver = Redis::get($redisKey);

                // Verifiquem que el seient estigui reservat per aquest usuari
                if (!$reserver || $reserver != $userId) {
                    throw new \Exception("La reserva del seient {$seatId} ha expirat o pertany a un altre usuari.");
                }

                $seat = Seat::with('zone')->findOrFail($seatId);
                $totalPrice += $seat->zone->price;
                $seatsToProcess[] = $seat;
            }

            // 1. Crear l'Ordre
            $order = Order::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'total_price' => $totalPrice,
                'status' => 'paid', // Simulem pagament aprovat
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            ]);

            // 2. Crear els Tiquets i tancar els seients
            foreach ($seatsToProcess as $seat) {
                Ticket::create([
                    'order_id' => $order->id,
                    'seat_id' => $seat->id,
                    'ticket_code' => strtoupper(Str::random(16)),
                    'price_paid' => $seat->zone->price,
                ]);

                // Actualitzem l'estat permanent a la BD
                $seat->update(['status' => 'occupied']);

                // Netegem Redis
                Redis::del("seat_reservation:{$seat->id}");

                // Notifiquem via WebSocket
                Redis::publish('seat-updates', json_encode([
                    'event_id' => $eventId,
                    'seat_id' => $seat->id,
                    'status' => 'occupied'
                ]));
            }

            // 3. Enviar correu de confirmació
            try {
                Mail::to($order->user->email)->send(new OrderConfirmed($order));
            }
            catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error enviant correu: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Compra finalitzada amb èxit!',
                'order' => $order->load('tickets'),
            ], 201);
        });
    }

    /**
     * Llistar ordres d'un usuari.
     */
    public function index()
    {
        $orders = Order::with(['event', 'tickets.seat.zone'])->where('user_id', Auth::id())->latest()->get();
        return response()->json($orders);
    }
}
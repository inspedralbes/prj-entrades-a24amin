<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display the specified event with its zones and seats.
     */
    public function show(string $id)
    {
        $event = Event::with(['zones.seats'])->findOrFail($id);

        return response()->json($event);
    }
}
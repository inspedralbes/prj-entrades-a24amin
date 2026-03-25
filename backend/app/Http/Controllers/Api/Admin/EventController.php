<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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
            'capacity' => 'required|integer',
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
            'capacity' => 'integer',
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
}
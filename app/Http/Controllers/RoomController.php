<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rooms = Room::orderBy('name')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Room::class);
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Room::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Room::create($data);
        return redirect()->route('rooms.index')->with('success', 'Salle créée.');
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $this->authorize('update', $room);
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $room->update($data);
        return redirect()->route('rooms.show', $room)->with('success', 'Salle mise à jour.');
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Salle supprimée.');
    }
}

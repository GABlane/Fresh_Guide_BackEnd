<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Floor;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('floor.building')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $floors      = Floor::with('building')->get();
        $facilities  = Facility::all();
        return view('admin.rooms.create', compact('floors', 'facilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'floor_id'    => 'required|exists:floors,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:rooms,code',
            'type'        => 'required|in:classroom,office,lab,restroom,other',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'facilities.*'=> 'exists:facilities,id',
        ]);

        $room = Room::create($data);

        if (!empty($data['facilities'])) {
            $room->facilities()->sync($data['facilities']);
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room created.');
    }

    public function edit(Room $room)
    {
        $floors     = Floor::with('building')->get();
        $facilities = Facility::all();
        return view('admin.rooms.edit', compact('room', 'floors', 'facilities'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'floor_id'    => 'required|exists:floors,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'type'        => 'required|in:classroom,office,lab,restroom,other',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'facilities.*'=> 'exists:facilities,id',
        ]);

        $room->update($data);
        $room->facilities()->sync($data['facilities'] ?? []);

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted.');
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        $rooms = Room::with('floor.building', 'facilities')->latest()->get();

        return response()->json(['success' => true, 'data' => $rooms, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'floor_id'     => 'required|exists:floors,id',
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:50|unique:rooms,code',
            'type'         => 'required|in:classroom,office,lab,restroom,other',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $room = Room::create($data);

        if (!empty($data['facilities'])) {
            $room->facilities()->sync($data['facilities']);
        }

        return response()->json(['success' => true, 'data' => $room->load('floor.building', 'facilities'), 'error' => null], 201);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $room->load('floor.building', 'facilities'), 'error' => null]);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $data = $request->validate([
            'floor_id'     => 'required|exists:floors,id',
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'type'         => 'required|in:classroom,office,lab,restroom,other',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $room->update($data);
        $room->facilities()->sync($data['facilities'] ?? []);

        return response()->json(['success' => true, 'data' => $room->load('floor.building', 'facilities'), 'error' => null]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

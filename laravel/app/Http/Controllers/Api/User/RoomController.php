<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        $rooms = Room::with('floor.building', 'facilities')->get();

        return response()->json(['success' => true, 'data' => $rooms, 'error' => null]);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $room->load('floor.building', 'facilities'), 'error' => null]);
    }
}

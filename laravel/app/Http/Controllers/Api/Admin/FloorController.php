<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Floor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index(): JsonResponse
    {
        $floors = Floor::with('building')->latest()->get();

        return response()->json(['success' => true, 'data' => $floors, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'number'      => 'required|integer|min:0',
            'name'        => 'required|string|max:255',
        ]);

        $floor = Floor::create($data);

        return response()->json(['success' => true, 'data' => $floor->load('building'), 'error' => null], 201);
    }

    public function show(Floor $floor): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $floor->load('building', 'rooms'), 'error' => null]);
    }

    public function update(Request $request, Floor $floor): JsonResponse
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'number'      => 'required|integer|min:0',
            'name'        => 'required|string|max:255',
        ]);

        $floor->update($data);

        return response()->json(['success' => true, 'data' => $floor, 'error' => null]);
    }

    public function destroy(Floor $floor): JsonResponse
    {
        $floor->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

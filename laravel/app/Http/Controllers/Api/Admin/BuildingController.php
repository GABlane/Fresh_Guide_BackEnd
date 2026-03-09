<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(): JsonResponse
    {
        $buildings = Building::withCount('floors')->latest()->get();

        return response()->json(['success' => true, 'data' => $buildings, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code',
            'description' => 'nullable|string',
        ]);

        $building = Building::create($data);

        return response()->json(['success' => true, 'data' => $building, 'error' => null], 201);
    }

    public function show(Building $building): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $building->load('floors'), 'error' => null]);
    }

    public function update(Request $request, Building $building): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'description' => 'nullable|string',
        ]);

        $building->update($data);

        return response()->json(['success' => true, 'data' => $building, 'error' => null]);
    }

    public function destroy(Building $building): JsonResponse
    {
        $building->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

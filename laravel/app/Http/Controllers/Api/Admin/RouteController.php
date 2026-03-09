<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampusRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        $routes = CampusRoute::with(['origin', 'destinationRoom', 'steps'])->latest()->get();

        return response()->json(['success' => true, 'data' => $routes, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'origin_id'            => 'required|exists:origins,id',
            'destination_room_id'  => 'required|exists:rooms,id',
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'steps'                => 'nullable|array',
            'steps.*.order'        => 'required|integer|min:1',
            'steps.*.instruction'  => 'required|string',
            'steps.*.direction'    => 'nullable|string|in:straight,left,right,up,down',
            'steps.*.landmark'     => 'nullable|string|max:255',
        ]);

        $route = CampusRoute::create($data);

        foreach ($data['steps'] ?? [] as $step) {
            $route->steps()->create($step);
        }

        return response()->json(['success' => true, 'data' => $route->load('origin', 'destinationRoom', 'steps'), 'error' => null], 201);
    }

    public function show(CampusRoute $route): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $route->load('origin', 'destinationRoom', 'steps'), 'error' => null]);
    }

    public function update(Request $request, CampusRoute $route): JsonResponse
    {
        $data = $request->validate([
            'origin_id'            => 'required|exists:origins,id',
            'destination_room_id'  => 'required|exists:rooms,id',
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'steps'                => 'nullable|array',
            'steps.*.order'        => 'required|integer|min:1',
            'steps.*.instruction'  => 'required|string',
            'steps.*.direction'    => 'nullable|string|in:straight,left,right,up,down',
            'steps.*.landmark'     => 'nullable|string|max:255',
        ]);

        $route->update($data);

        // Replace steps in bulk
        $route->steps()->delete();
        foreach ($data['steps'] ?? [] as $step) {
            $route->steps()->create($step);
        }

        return response()->json(['success' => true, 'data' => $route->load('origin', 'destinationRoom', 'steps'), 'error' => null]);
    }

    public function destroy(CampusRoute $route): JsonResponse
    {
        $route->delete(); // steps cascade via FK

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

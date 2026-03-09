<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\CampusRoute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function show(Request $request, int $roomId): JsonResponse
    {
        $request->validate([
            'origin_id' => 'required|exists:origins,id',
        ]);

        $route = CampusRoute::with('steps')
            ->where('destination_room_id', $roomId)
            ->where('origin_id', $request->query('origin_id'))
            ->first();

        if (!$route) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'error'   => 'No route found for this room and origin combination.',
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $route, 'error' => null]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Origin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OriginController extends Controller
{
    public function index(): JsonResponse
    {
        $origins = Origin::latest()->get();

        return response()->json(['success' => true, 'data' => $origins, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:origins,code',
            'description' => 'nullable|string',
        ]);

        $origin = Origin::create($data);

        return response()->json(['success' => true, 'data' => $origin, 'error' => null], 201);
    }

    public function show(Origin $origin): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $origin, 'error' => null]);
    }

    public function update(Request $request, Origin $origin): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:origins,code,' . $origin->id,
            'description' => 'nullable|string',
        ]);

        $origin->update($data);

        return response()->json(['success' => true, 'data' => $origin, 'error' => null]);
    }

    public function destroy(Origin $origin): JsonResponse
    {
        $origin->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

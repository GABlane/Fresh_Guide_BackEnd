<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(): JsonResponse
    {
        $facilities = Facility::latest()->get();

        return response()->json(['success' => true, 'data' => $facilities, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $facility = Facility::create($data);

        return response()->json(['success' => true, 'data' => $facility, 'error' => null], 201);
    }

    public function show(Facility $facility): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $facility, 'error' => null]);
    }

    public function update(Request $request, Facility $facility): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $facility->update($data);

        return response()->json(['success' => true, 'data' => $facility, 'error' => null]);
    }

    public function destroy(Facility $facility): JsonResponse
    {
        $facility->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }
}

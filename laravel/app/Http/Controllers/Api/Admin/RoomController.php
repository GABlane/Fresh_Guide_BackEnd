<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'location'     => 'nullable|string|max:255',
            'image_url'    => 'nullable|string|max:1024',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('rooms', 'public');
        }

        $room = Room::create($this->extractRoomPayload($data));

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
            'location'     => 'nullable|string|max:255',
            'image_url'    => 'nullable|string|max:1024',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteStoredImageIfLocal($room->image_url);
            $data['image_url'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($this->extractRoomPayload($data));
        $room->facilities()->sync($data['facilities'] ?? []);

        return response()->json(['success' => true, 'data' => $room->load('floor.building', 'facilities'), 'error' => null]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $this->deleteStoredImageIfLocal($room->image_url);
        $room->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }

    private function extractRoomPayload(array $data): array
    {
        return [
            'floor_id' => $data['floor_id'],
            'name' => $data['name'],
            'code' => $data['code'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
            'image_url' => $data['image_url'] ?? null,
        ];
    }

    private function deleteStoredImageIfLocal(?string $imageUrl): void
    {
        if (empty($imageUrl)) {
            return;
        }

        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($imageUrl);
    }
}

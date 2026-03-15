<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    public function upload(Request $request, Room $room): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $this->deleteStoredImageIfLocal($room->image_url);

        $path = $request->file('image')->store('rooms', 'public');
        $room->update(['image_url' => $path]);

        return response()->json([
            'success' => true,
            'data' => $room->fresh()->load('floor.building', 'facilities'),
            'error' => null,
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $this->deleteStoredImageIfLocal($room->image_url);
        $room->update(['image_url' => null]);

        return response()->json([
            'success' => true,
            'data' => $room->fresh()->load('floor.building', 'facilities'),
            'error' => null,
        ]);
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

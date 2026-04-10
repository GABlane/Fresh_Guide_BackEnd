<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\SavedRoom;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        $favorites = SavedRoom::query()
            ->where('user_id', $user->id)
            ->orderByDesc('saved_at')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (SavedRoom $favorite) => $this->serializeFavorite($favorite));

        return response()->json([
            'success' => true,
            'data' => $favorites,
            'error' => null,
        ]);
    }

    public function save(Request $request, int $roomId): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        if (! Room::query()->whereKey($roomId)->exists()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => 'Room not found.',
            ], 404);
        }

        $favorite = SavedRoom::query()
            ->where('user_id', $user->id)
            ->where('room_id', $roomId)
            ->first();

        if ($favorite === null) {
            $favorite = SavedRoom::query()->create([
                'user_id' => $user->id,
                'room_id' => $roomId,
                'saved_at' => now(),
            ]);
        } else {
            $favorite->saved_at = now();
            $favorite->save();
        }

        return response()->json([
            'success' => true,
            'data' => $this->serializeFavorite($favorite->fresh()),
            'error' => null,
        ]);
    }

    public function destroy(Request $request, int $roomId): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        SavedRoom::query()
            ->where('user_id', $user->id)
            ->where('room_id', $roomId)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'error' => null,
        ]);
    }

    private function resolveStudent(Request $request): ?User
    {
        /** @var User|null $user */
        $user = $request->user();
        if ($user === null || $user->isAdmin()) {
            return null;
        }

        return $user;
    }

    private function forbidden(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'error' => 'Forbidden.',
        ], 403);
    }

    private function serializeFavorite(SavedRoom $favorite): array
    {
        return [
            'room_id' => $favorite->room_id,
            'saved_at' => optional($favorite->saved_at)->toISOString(),
            'updated_at' => optional($favorite->updated_at)->toISOString(),
        ];
    }
}

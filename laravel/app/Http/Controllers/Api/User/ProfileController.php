<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        return response()->json([
            'success' => true,
            'data' => $this->serializeProfile($user),
            'error' => null,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'course_section' => ['nullable', 'string', 'max:100'],
        ]);

        $user->name = trim($data['name']);
        $user->course_section = isset($data['course_section']) ? trim((string) $data['course_section']) : null;
        if ($user->course_section === '') {
            $user->course_section = null;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $this->serializeProfile($user->fresh()),
            'error' => null,
        ]);
    }

    public function uploadPhoto(Request $request): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $this->deleteStoredImageIfLocal($user->profile_photo_path);

        $path = $request->file('image')->store('profiles', 'public');
        $user->profile_photo_path = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $this->serializeProfile($user->fresh()),
            'error' => null,
        ]);
    }

    public function deletePhoto(Request $request): JsonResponse
    {
        $user = $this->resolveStudent($request);
        if ($user === null) {
            return $this->forbidden();
        }

        $this->deleteStoredImageIfLocal($user->profile_photo_path);

        $user->profile_photo_path = null;
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $this->serializeProfile($user->fresh()),
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

    private function serializeProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'student_id' => $user->student_id,
            'name' => $user->name,
            'course_section' => $user->course_section,
            'profile_photo_url' => $user->profile_photo_url,
            'updated_at' => optional($user->updated_at)->toISOString(),
        ];
    }

    private function deleteStoredImageIfLocal(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

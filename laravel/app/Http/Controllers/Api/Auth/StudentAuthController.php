<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'string', 'regex:/^\d{8}-(S|N|C)$/'],
            'name'       => 'nullable|string|max:255',
        ]);

        $campusCode = substr($request->student_id, -1);

        $user = User::firstOrCreate(
            ['student_id' => $request->student_id],
            [
                'name'        => $request->name ?? $request->student_id,
                'campus_code' => $campusCode,
                'role'        => User::ROLE_USER,
                'is_active'   => true,
            ]
        );

        $user->last_login_at = now();
        $user->save();

        $token = $user->createToken('student-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'          => $user->id,
                    'student_id'  => $user->student_id,
                    'campus_code' => $user->campus_code,
                    'name'        => $user->name,
                    'role'        => $user->role,
                ],
            ],
            'error' => null,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $user->id,
                'student_id'  => $user->student_id,
                'campus_code' => $user->campus_code,
                'name'        => $user->name,
                'role'        => $user->role,
            ],
            'error' => null,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'data'    => null,
            'error'   => null,
        ]);
    }
}

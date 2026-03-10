<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'string', 'regex:/^\d{8}-(S|N|C)$/'],
            'name'       => 'nullable|string|max:255',
        ]);

        $studentId = strtoupper(trim($request->student_id));
        $campusCode = substr($studentId, -1);
        $generatedEmail = Str::lower(str_replace('-', '.', $studentId)) . '@students.freshguide.local';
        $studentRole = DB::getDriverName() === 'sqlite' ? 'viewer' : User::ROLE_USER;

        $user = User::firstOrCreate(
            ['student_id' => $studentId],
            [
                'name'        => $request->name ?? $studentId,
                'email'       => $generatedEmail,
                'password'    => Hash::make(Str::random(40)),
                'campus_code' => $campusCode,
                'role'        => $studentRole,
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
                    'role'        => User::ROLE_USER,
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
                'role'        => $user->isAdmin() ? User::ROLE_ADMIN : User::ROLE_USER,
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

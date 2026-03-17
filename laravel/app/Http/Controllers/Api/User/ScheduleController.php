<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\ScheduleEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $entries = ScheduleEntry::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('day_of_week')
            ->orderBy('start_minutes')
            ->get();

        return response()->json(['success' => true, 'data' => $entries, 'error' => null]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate($this->rulesFor($user->id));
        $data['user_id'] = $user->id;

        $entry = ScheduleEntry::create($data);

        return response()->json(['success' => true, 'data' => $entry, 'error' => null], 201);
    }

    public function update(Request $request, int $schedule): JsonResponse
    {
        $user = $request->user();
        $entry = $this->findOwnedEntry($user->id, $schedule);
        if ($entry === null) {
            return response()->json(['success' => false, 'data' => null, 'error' => 'Schedule not found.'], 404);
        }

        $data = $request->validate($this->rulesFor($user->id, $entry->id));
        $entry->update($data);

        return response()->json(['success' => true, 'data' => $entry->fresh(), 'error' => null]);
    }

    public function destroy(Request $request, int $schedule): JsonResponse
    {
        $entry = $this->findOwnedEntry($request->user()->id, $schedule);
        if ($entry === null) {
            return response()->json(['success' => false, 'data' => null, 'error' => 'Schedule not found.'], 404);
        }

        $entry->delete();

        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }

    private function findOwnedEntry(int $userId, int $id): ?ScheduleEntry
    {
        return ScheduleEntry::query()
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }

    private function rulesFor(int $userId, ?int $ignoreId = null): array
    {
        $clientUuidRule = Rule::unique('schedule_entries', 'client_uuid')
            ->where(fn ($q) => $q->where('user_id', $userId));

        if ($ignoreId !== null) {
            $clientUuidRule = $clientUuidRule->ignore($ignoreId);
        }

        return [
            'client_uuid' => ['required', 'uuid', $clientUuidRule],
            'title' => ['required', 'string', 'max:255'],
            'course_code' => ['nullable', 'string', 'max:100'],
            'instructor' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'color_hex' => ['nullable', 'string', 'max:20'],
            'day_of_week' => ['required', 'integer', 'between:1,6'],
            'start_minutes' => ['required', 'integer', 'between:0,1439'],
            'end_minutes' => ['required', 'integer', 'between:1,1440', 'gt:start_minutes'],
            'is_online' => ['required', 'boolean'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'online_platform' => ['nullable', 'string', 'max:255'],
            'reminder_minutes' => ['required', 'integer', 'min:0', 'max:180'],
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Floor;
use App\Models\Room;
use App\Models\SavedRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_list_save_and_delete_favorites(): void
    {
        $student = $this->createStudent('20230001-S');
        $room = $this->createRoom();

        Sanctum::actingAs($student);

        $this->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', []);

        $this->putJson("/api/favorites/{$room->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.room_id', $room->id)
            ->assertJsonPath('error', null);

        $this->assertDatabaseHas('saved_rooms', [
            'user_id' => $student->id,
            'room_id' => $room->id,
        ]);

        $this->getJson('/api/favorites')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.room_id', $room->id);

        $this->deleteJson("/api/favorites/{$room->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', null)
            ->assertJsonPath('error', null);

        $this->assertDatabaseMissing('saved_rooms', [
            'user_id' => $student->id,
            'room_id' => $room->id,
        ]);
    }

    public function test_saving_same_room_twice_keeps_single_row_and_refreshes_saved_at(): void
    {
        $student = $this->createStudent('20230002-S');
        $room = $this->createRoom();

        Sanctum::actingAs($student);

        Carbon::setTestNow('2026-04-11 08:00:00');
        $this->putJson("/api/favorites/{$room->id}")->assertOk();

        Carbon::setTestNow('2026-04-11 08:10:00');
        $this->putJson("/api/favorites/{$room->id}")->assertOk();

        $this->assertSame(1, SavedRoom::query()
            ->where('user_id', $student->id)
            ->where('room_id', $room->id)
            ->count());

        $favorite = SavedRoom::query()
            ->where('user_id', $student->id)
            ->where('room_id', $room->id)
            ->first();

        $this->assertNotNull($favorite);
        $this->assertSame('2026-04-11T08:10:00.000000Z', $favorite->saved_at?->toISOString());

        Carbon::setTestNow();
    }

    public function test_admin_is_forbidden_from_favorites_endpoints(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@freshguide.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/favorites')
            ->assertStatus(403)
            ->assertJsonPath('success', false);
    }

    public function test_saving_missing_room_returns_not_found(): void
    {
        $student = $this->createStudent('20230003-S');
        Sanctum::actingAs($student);

        $this->putJson('/api/favorites/999999')
            ->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('error', 'Room not found.');
    }

    private function createStudent(string $studentId): User
    {
        return User::create([
            'name' => $studentId,
            'student_id' => $studentId,
            'email' => strtolower($studentId) . '@students.freshguide.local',
            'password' => Hash::make('secret'),
            'role' => 'viewer',
            'is_active' => true,
        ]);
    }

    private function createRoom(): Room
    {
        $building = Building::create([
            'name' => 'Main Building',
            'code' => 'MAIN',
            'description' => 'Main',
        ]);

        $floor = Floor::create([
            'building_id' => $building->id,
            'number' => 1,
            'name' => '1st Floor',
        ]);

        return Room::create([
            'floor_id' => $floor->id,
            'name' => 'Lecture Room 101',
            'code' => 'MAIN-1-LR101',
            'type' => 'classroom',
            'description' => 'Test room',
        ]);
    }
}

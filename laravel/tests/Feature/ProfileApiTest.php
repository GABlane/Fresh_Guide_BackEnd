<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_fetch_and_update_profile(): void
    {
        $student = User::create([
            'name' => '20230054-S',
            'student_id' => '20230054-S',
            'email' => '20230054.s@students.freshguide.local',
            'password' => Hash::make('secret'),
            'role' => 'viewer',
            'is_active' => true,
        ]);

        Sanctum::actingAs($student);

        $this->getJson('/api/profile')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.student_id', '20230054-S')
            ->assertJsonPath('data.name', '20230054-S');

        $this->putJson('/api/profile', [
            'name' => 'Test Student',
            'course_section' => 'BSCS 3A',
        ])->assertOk()
            ->assertJsonPath('data.name', 'Test Student')
            ->assertJsonPath('data.course_section', 'BSCS 3A');

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'name' => 'Test Student',
            'course_section' => 'BSCS 3A',
        ]);
    }

    public function test_student_can_upload_and_delete_profile_photo(): void
    {
        Storage::fake('public');

        $student = User::create([
            'name' => 'Test Student',
            'student_id' => '20230054-S',
            'email' => '20230054.s@students.freshguide.local',
            'password' => Hash::make('secret'),
            'role' => 'viewer',
            'is_active' => true,
        ]);

        Sanctum::actingAs($student);

        $uploadResponse = $this->postJson('/api/profile/photo', [
            'image' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
        ]);

        $uploadResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.student_id', '20230054-S');

        $student->refresh();
        $this->assertNotNull($student->profile_photo_path);
        Storage::disk('public')->assertExists($student->profile_photo_path);

        $this->deleteJson('/api/profile/photo')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.profile_photo_url', null);

        $student->refresh();
        $this->assertNull($student->profile_photo_path);
    }

    public function test_admin_is_forbidden_from_student_profile_endpoints(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@freshguide.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/profile')
            ->assertStatus(403)
            ->assertJsonPath('success', false);
    }
}

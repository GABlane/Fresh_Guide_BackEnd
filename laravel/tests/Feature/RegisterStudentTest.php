<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterStudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register_with_student_id_only(): void
    {
        $response = $this->postJson('/api/register', [
            'student_id' => '20230054-S',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.student_id', '20230054-S');

        $this->assertDatabaseHas('users', [
            'student_id' => '20230054-S',
            'email' => '20230054.s@students.freshguide.local',
        ]);
    }
}

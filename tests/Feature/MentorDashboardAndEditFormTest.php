<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\User;
use App\Models\WorkoutExercise;
use App\Models\WorkoutProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentorDashboardAndEditFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_renders_even_when_the_mentor_profile_record_is_missing(): void
    {
        $user = User::factory()->create([
            'role' => 'mentor',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mentor.dashboard'));

        $response->assertOk();
        $response->assertSee('Halo');
    }

    public function test_edit_form_shows_the_current_uploaded_video_for_existing_exercises(): void
    {
        $user = User::factory()->create([
            'role' => 'mentor',
            'is_active' => true,
        ]);

        $mentor = Mentor::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'bio' => 'Mentor',
            'specialization' => 'Strength',
        ]);

        $program = WorkoutProgram::create([
            'mentor_id' => $mentor->id,
            'title' => 'Strength Plan',
            'category' => 'Kekuatan',
            'level' => 'pemula',
            'description' => 'Program test',
            'status' => 'draft',
        ]);

        WorkoutExercise::create([
            'workout_program_id' => $program->id,
            'name' => 'Squat',
            'description' => 'Test',
            'video_url' => 'https://example.com/video.mp4',
            'sets' => 3,
            'reps' => 12,
            'rest_seconds' => 30,
            'order_index' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mentor.programs.edit', $program));

        $response->assertOk();
        $response->assertSee('Video saat ini');
        $response->assertSee('https://example.com/video.mp4');
    }
}

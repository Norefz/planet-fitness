<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MentorProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    private function actingMentor(): array
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
            'is_verified' => true,
        ]);

        $this->actingAs($user);

        return [$user, $mentor];
    }

    public function test_uploading_a_profile_photo_saves_the_cloudinary_url_on_the_mentor(): void
    {
        [$user, $mentor] = $this->actingMentor();

        Http::fake([
            'api.cloudinary.com/*' => Http::response([
                'secure_url' => 'https://res.cloudinary.com/demo/image/upload/v1/mentor-profile-pictures/photo.jpg',
                'public_id' => 'mentor-profile-pictures/photo',
            ], 200),
        ]);

        $response = $this->put(route('mentor.profile.update'), [
            'name' => $user->name,
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertRedirect(route('mentor.profile.edit'));

        $mentor->refresh();
        $this->assertSame(
            'https://res.cloudinary.com/demo/image/upload/v1/mentor-profile-pictures/photo.jpg',
            $mentor->profile_photo_url
        );
        $this->assertSame('mentor-profile-pictures/photo', $mentor->profile_photo_public_id);

        // The uploaded photo should now also be reflected by the shared
        // avatar_url accessor used across the app (navbar, sidebar, etc.).
        $this->assertSame($mentor->profile_photo_url, $user->fresh()->avatar_url);
    }

    public function test_deleting_the_profile_photo_clears_it_from_the_mentor(): void
    {
        [$user, $mentor] = $this->actingMentor();

        $mentor->update([
            'profile_photo_url' => 'https://res.cloudinary.com/demo/image/upload/v1/mentor-profile-pictures/photo.jpg',
            'profile_photo_public_id' => 'mentor-profile-pictures/photo',
        ]);

        Http::fake([
            'api.cloudinary.com/*' => Http::response([], 200),
        ]);

        $response = $this->delete(route('mentor.profile.photo.destroy'));

        $response->assertRedirect(route('mentor.profile.edit'));

        $mentor->refresh();
        $this->assertNull($mentor->profile_photo_url);
        $this->assertNull($mentor->profile_photo_public_id);
    }
}

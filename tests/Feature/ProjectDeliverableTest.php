<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectDeliverableTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_upload_deliverable()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->users()->attach($user->id, ['role' => 'member']);

        $file = UploadedFile::fake()->create('report.pdf', 100, 'application/pdf');

        // use multipart POST (not JSON) so UploadedFile is handled correctly
        $response = $this->actingAs($user)->post(route('projects.deliverables.store', $project->id), [
            'file' => $file,
        ]);

        $response->assertStatus(201);

        // controller returns the deliverable record containing the stored path
        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);
    }
}

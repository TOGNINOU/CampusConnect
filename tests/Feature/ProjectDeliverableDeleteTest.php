<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectDeliverable;

class ProjectDeliverableDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_deliverable_and_file_is_removed()
    {
        Storage::fake('public');

        $owner = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($owner);

        // create project
        $this->post(route('projects.store'), [
            'title' => 'Projet delete file',
            'description' => 'desc',
            'supervisor_id' => $teacher->id,
        ]);

        $project = Project::where('title', 'Projet delete file')->firstOrFail();

        // upload a deliverable via controller
        $file = UploadedFile::fake()->create('deliver.pdf', 100, 'application/pdf');

        $resp = $this->post(route('projects.deliverables.store', $project->id), [
            'file' => $file,
        ]);

        $resp->assertStatus(201);

        $deliverable = ProjectDeliverable::where('project_id', $project->id)->firstOrFail();

        // file exists
        Storage::disk('public')->assertExists($deliverable->path);

        // now delete as owner
        $delResp = $this->delete(route('projects.deliverables.destroy', [$project->id, $deliverable->id]));
        $delResp->assertNoContent();

        // DB record removed and file deleted
        $this->assertDatabaseMissing('project_deliverables', ['id' => $deliverable->id]);
        Storage::disk('public')->assertMissing($deliverable->path);
    }
}

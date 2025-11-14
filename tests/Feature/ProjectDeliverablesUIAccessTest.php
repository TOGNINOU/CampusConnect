<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectDeliverable;

class ProjectDeliverablesUIAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_see_deliverables_and_non_members_do_not_see_delete_button()
    {
        Storage::fake('public');

        $owner = User::factory()->create(['role' => 'student']);
        $member = User::factory()->create(['role' => 'student']);
        $outsider = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        // owner creates project and adds member
        $this->actingAs($owner);
        $this->post(route('projects.store'), [
            'title' => 'Projet UI',
            'description' => 'desc',
            'supervisor_id' => $teacher->id,
            'members' => [$member->id],
        ]);

        $project = Project::where('title', 'Projet UI')->firstOrFail();

        // upload a deliverable
        $file = UploadedFile::fake()->create('deliver.pdf', 100, 'application/pdf');
        $this->post(route('projects.deliverables.store', $project->id), ['file' => $file]);
        $deliverable = ProjectDeliverable::where('project_id', $project->id)->firstOrFail();

    // owner should see delete button
    $this->actingAs($owner);
    $resp = $this->get(route('projects.show', $project->id));
    $resp->assertStatus(200);
    $resp->assertSee('Supprimer');

    // member should NOT see delete button (only owner/supervisor/admin can delete)
    $this->actingAs($member);
    $respMember = $this->get(route('projects.show', $project->id));
    $respMember->assertStatus(200);
    $respMember->assertDontSee('Supprimer');

    // outsider cannot view the project (forbidden)
    $this->actingAs($outsider);
    $resp2 = $this->get(route('projects.show', $project->id));
    $resp2->assertForbidden();
    }
}

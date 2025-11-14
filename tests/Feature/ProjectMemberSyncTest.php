<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Project;

class ProjectMemberSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_syncs_members_and_preserves_owner()
    {
        // create users
        $owner = User::factory()->create(['role' => 'student']);
        $studentA = User::factory()->create(['role' => 'student']);
        $studentB = User::factory()->create(['role' => 'student']);

        $this->actingAs($owner);

        // owner creates project and attaches self as owner
        $response = $this->post(route('projects.store'), [
            'title' => 'Projet sync',
            'description' => 'Test sync',
            'members' => [$studentA->id],
        ]);

        $response->assertRedirect();

        $project = Project::where('title', 'Projet sync')->firstOrFail();

        // owner and studentA present
        $this->assertDatabaseHas('project_user', ['project_id' => $project->id, 'user_id' => $owner->id, 'role' => 'owner']);
        $this->assertDatabaseHas('project_user', ['project_id' => $project->id, 'user_id' => $studentA->id, 'role' => 'member']);

        // now update members to [studentB] (owner must remain)
        $updateResp = $this->put(route('projects.update', $project->id), [
            'members' => [$studentB->id],
        ]);

        $updateResp->assertRedirect();

        // owner still owner, studentA removed, studentB present
        $this->assertDatabaseHas('project_user', ['project_id' => $project->id, 'user_id' => $owner->id, 'role' => 'owner']);
        $this->assertDatabaseMissing('project_user', ['project_id' => $project->id, 'user_id' => $studentA->id]);
        $this->assertDatabaseHas('project_user', ['project_id' => $project->id, 'user_id' => $studentB->id, 'role' => 'member']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectMembersTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_add_and_remove_members()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->attach($owner->id, ['role' => 'owner']);

        // add member
        $response = $this->actingAs($owner)->postJson(route('projects.members.store', $project->id), [
            'user_id' => $member->id,
            'role' => 'member',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('project_user', ['project_id' => $project->id, 'user_id' => $member->id]);

        // remove member
        $response = $this->actingAs($owner)->delete(route('projects.members.destroy', ['project' => $project->id, 'user' => $member->id]));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('project_user', ['project_id' => $project->id, 'user_id' => $member->id]);
    }

    public function test_non_owner_cannot_manage_members()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $member = User::factory()->create();

        $project = Project::factory()->create();
        $project->users()->attach($owner->id, ['role' => 'owner']);

        $response = $this->actingAs($other)->postJson(route('projects.members.store', $project->id), [
            'user_id' => $member->id,
        ]);

        $response->assertStatus(403);
    }
}

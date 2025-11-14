<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectSupervisorTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_view_projects_they_supervise()
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $project = Project::factory()->create(['supervisor_id' => $supervisor->id]);

        $response = $this->actingAs($supervisor)->getJson(route('projects.show', $project->id));

        $response->assertStatus(200)->assertJsonFragment(['id' => $project->id]);
    }

    public function test_non_supervisor_cannot_view_unrelated_project()
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $other = User::factory()->create();
        $project = Project::factory()->create(['supervisor_id' => $supervisor->id]);

        $response = $this->actingAs($other)->getJson(route('projects.show', $project->id));
        $response->assertStatus(403);
    }
}

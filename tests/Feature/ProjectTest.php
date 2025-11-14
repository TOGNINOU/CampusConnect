<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_project()
    {
        // Only students are allowed to create projects
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->postJson(route('projects.store'), [
            'title' => 'Team Project',
            'description' => 'Description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', ['title' => 'Team Project']);
    }
}

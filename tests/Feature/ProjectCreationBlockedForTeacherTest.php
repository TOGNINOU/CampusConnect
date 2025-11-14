<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProjectCreationBlockedForTeacherTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_cannot_create_project()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($teacher);

        $response = $this->post(route('projects.store'), [
            'title' => 'Projet interdit',
            'description' => 'Les enseignants ne doivent pas pouvoir créer',
        ]);

        $response->assertForbidden();
    }
}

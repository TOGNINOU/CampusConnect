<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Project;

class ProjectCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_project_with_teacher_supervisor_and_upload_report()
    {
        Storage::fake('public');

        // create a teacher and a student
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student);

        $file = UploadedFile::fake()->create('report.pdf', 200, 'application/pdf');

        $response = $this->post(route('projects.store'), [
            'title' => 'Projet Test',
            'description' => 'Description du projet',
            'supervisor_id' => $teacher->id,
            'members' => [],
            'report' => $file,
        ]);

        // should redirect to project show
        $response->assertRedirect();

        $this->assertDatabaseHas('projects', ['title' => 'Projet Test', 'supervisor_id' => $teacher->id]);

        $project = Project::where('title', 'Projet Test')->first();
        $this->assertNotNull($project);

        // deliverable record exists
        $this->assertDatabaseHas('project_deliverables', ['project_id' => $project->id, 'uploader_id' => $student->id, 'filename' => 'report.pdf']);

        // file exists on disk
        Storage::disk('public')->assertExists("projects/{$project->id}/reports/" . $file->hashName());
    }
}

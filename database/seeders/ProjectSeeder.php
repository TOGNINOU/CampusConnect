<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectDeliverable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // create supervisor and students
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $students = User::factory()->count(3)->create();

        // create a project
        $project = Project::factory()->create([
            'title' => 'Demo Team Project',
            'description' => 'Un projet de démonstration généré par ProjectSeeder.',
            'supervisor_id' => $supervisor->id,
        ]);

        // attach members (first student will be owner)
        $project->users()->attach($students[0]->id, ['role' => 'owner']);
        $project->users()->attach($students[1]->id, ['role' => 'member']);
        $project->users()->attach($students[2]->id, ['role' => 'member']);

        // add a demo deliverable file to storage and DB
        Storage::disk('public')->put('projects/'.$project->id.'/demo.txt', 'Demo content');

        ProjectDeliverable::create([
            'project_id' => $project->id,
            'uploader_id' => $students[0]->id,
            'filename' => 'demo.txt',
            'path' => 'projects/'.$project->id.'/demo.txt',
            'mime_type' => 'text/plain',
            'size' => 12,
        ]);
    }
}

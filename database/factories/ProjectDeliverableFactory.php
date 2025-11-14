<?php

namespace Database\Factories;

use App\Models\ProjectDeliverable;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectDeliverableFactory extends Factory
{
    protected $model = ProjectDeliverable::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'uploader_id' => User::factory(),
            'filename' => $this->faker->word() . '.pdf',
            'path' => 'projects/dummy.pdf',
            'mime_type' => 'application/pdf',
            'size' => 12345,
        ];
    }
}

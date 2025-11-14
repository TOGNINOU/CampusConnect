<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDeliverable extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'uploader_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}

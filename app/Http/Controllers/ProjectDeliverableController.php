<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDeliverable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectDeliverableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        return response()->json($project->deliverables()->get());
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('uploadDeliverable', $project);

        $data = $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,zip,txt',
        ]);

        $file = $data['file'];
        $filename = time().'_'.preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('projects/'.$project->id, $filename, 'public');

        $deliverable = ProjectDeliverable::create([
            'project_id' => $project->id,
            'uploader_id' => $request->user()->id,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json($deliverable, 201);
    }

    public function download(Project $project, ProjectDeliverable $deliverable)
    {
        $this->authorize('view', $project);
        return Storage::disk('public')->download($deliverable->path, $deliverable->filename);
    }

    public function destroy(Project $project, ProjectDeliverable $deliverable)
    {
        $this->authorize('delete', $project);
        Storage::disk('public')->delete($deliverable->path);
        $deliverable->delete();
        return response()->noContent();
    }
}

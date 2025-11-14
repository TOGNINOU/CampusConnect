<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\ProjectDeliverable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        // show projects where user is a member or supervisor or admin
        if ($user->isAdmin() ?? false) {
            $projects = Project::with('users', 'supervisor')->get();
        } else {
            $projects = Project::whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->orWhere('supervisor_id', $user->id)->with('users', 'supervisor')->get();
        }

        // If request expects JSON, return JSON (API), otherwise render a simple view
        if (request()->wantsJson()) {
            return response()->json($projects);
        }

        return view('projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        $this->authorize('create', Project::class);

        // provide a list of available teachers for supervisor selection
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = User::where('role', 'student')->orderBy('name')->get();

        // Render the create form for web users
        return view('projects.create', ['teachers' => $teachers, 'students' => $students]);
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('projects.edit', ['project' => $project, 'teachers' => $teachers, 'students' => $students]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:users,id',
            'report' => 'nullable|file|max:10240|mimes:pdf,doc,docx,zip,txt',
            'members' => 'array',
            'members.*' => 'exists:users,id',
        ]);

        // if a supervisor is provided, ensure they are a teacher
        if (!empty($data['supervisor_id'])) {
            $sup = User::find($data['supervisor_id']);
            if (!$sup || !$sup->isTeacher()) {
                return back()->withErrors(['supervisor_id' => 'Le superviseur doit être un enseignant.'])->withInput();
            }
        }

        $project = Project::create(["title" => $data['title'], "description" => $data['description'] ?? null, 'supervisor_id' => $data['supervisor_id'] ?? null]);

        // attach owner (creator)
        $project->users()->attach(Auth::id(), ['role' => 'owner']);

        // attach additional members if any
        if (!empty($data['members'])) {
            foreach ($data['members'] as $memberId) {
                if ($memberId != Auth::id()) {
                    $project->users()->attach($memberId, ['role' => 'member']);
                }
            }
        }

        // handle an optional initial report upload from the creator
        if ($request->hasFile('report')) {
            $file = $request->file('report');
            $path = $file->store("projects/{$project->id}/reports", 'public');

            ProjectDeliverable::create([
                'project_id' => $project->id,
                'uploader_id' => Auth::id(),
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json($project->load('users', 'supervisor'), 201);
        }

        return redirect()->route('projects.show', $project->id);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        if (request()->wantsJson()) {
            return response()->json($project->load('users', 'deliverables', 'supervisor'));
        }

        return view('projects.show', ['project' => $project->load('users', 'deliverables', 'supervisor')]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:users,id',
            'report' => 'nullable|file|max:10240|mimes:pdf,doc,docx,zip,txt',
        ]);

        // if a supervisor is provided, ensure they are a teacher
        if (array_key_exists('supervisor_id', $data) && !empty($data['supervisor_id'])) {
            $sup = User::find($data['supervisor_id']);
            if (!$sup || !$sup->isTeacher()) {
                return back()->withErrors(['supervisor_id' => 'Le superviseur doit être un enseignant.'])->withInput();
            }
        }

        $project->update($data);

        // handle membership updates if provided (keep owner role)
        if ($request->has('members')) {
            $members = $request->input('members', []);

            // find current owner (creator) or fallback to authenticated user
            $owner = $project->users()->wherePivot('role', 'owner')->first();
            $ownerId = $owner ? $owner->id : Auth::id();

            $sync = [ $ownerId => ['role' => 'owner'] ];
            foreach ($members as $mId) {
                if ($mId == $ownerId) continue;
                $sync[$mId] = ['role' => 'member'];
            }

            $project->users()->sync($sync);
        }

        // handle optional report upload
        if ($request->hasFile('report')) {
            $file = $request->file('report');
            $path = $file->store("projects/{$project->id}/reports", 'public');

            ProjectDeliverable::create([
                'project_id' => $project->id,
                'uploader_id' => Auth::id(),
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        // For API clients return JSON, for web users redirect to the project page
        if ($request->wantsJson()) {
            return response()->json($project->fresh()->load('users', 'supervisor'));
        }

        return redirect()->route('projects.show', $project->id)->with('status', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project)
    {
        $this->authorize('view', $project);
        return response()->json($project->users()->get());
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('manageMembers', $project);

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string',
        ]);

        $project->users()->attach($data['user_id'], ['role' => $data['role'] ?? 'member']);

        return response()->json($project->users()->find($data['user_id']), 201);
    }

    public function destroy(Project $project, $userId)
    {
        $this->authorize('manageMembers', $project);
        $project->users()->detach($userId);
        return response()->noContent();
    }
}

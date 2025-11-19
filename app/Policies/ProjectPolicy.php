<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user != null;
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->isAdmin() ?? false) return true;
        if ($project->supervisor_id && $project->supervisor_id === $user->id) return true;
        return $project->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        // seuls les étudiants peuvent créer des projets
        return $user != null && $user->isStudent();
    }

    public function update(User $user, Project $project): bool
    {
        if ($user->isAdmin() ?? false) return true;
        if ($project->supervisor_id && $project->supervisor_id === $user->id) return true;
        return $project->users()->wherePivot('role', 'owner')->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    public function manageMembers(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    public function uploadDeliverable(User $user, Project $project): bool
    {
        // membres, propriétaires, superviseurs et administrateurs peuvent téléverser
        if ($user->isAdmin() ?? false) return true;
        if ($project->supervisor_id && $project->supervisor_id === $user->id) return true;
        return $project->users()->where('user_id', $user->id)->exists();
    }
}

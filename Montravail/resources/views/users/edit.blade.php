@extends('layouts.app')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Modifier utilisateur</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe (laisser vide pour conserver)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning w-100">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection

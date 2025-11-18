@extends('layouts.app')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Ajouter un utilisateur</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="teacher">Enseignant</option>
                        <option value="student">Étudiant</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Ajouter</button>
            </form>
        </div>
    </div>
</div>
@endsection

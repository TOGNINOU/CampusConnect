@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Gestion des utilisateurs</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success">Ajouter un utilisateur</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($users->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur trouvé</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

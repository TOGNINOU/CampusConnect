@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container py-4">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Bienvenue sur CampusConnect</h2>
        <p class="text-muted">Bonjour, <strong>{{ Auth::user()->name }}</strong> ({{ ucfirst(Auth::user()->role) }})</p>
    </div>

    <div class="row g-4">
        {{-- Section enseignant --}}
        @if(Auth::user()->role === 'teacher')
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Réserver une salle ou du matériel</h5>
                    <p class="text-muted small">Planifier un cours et emprunter le matériel nécessaire.</p>
                    <a href="{{ route('reservations.create') }}" class="btn btn-primary w-100">Faire une réservation</a>
                    <a href="{{ route('reservations.index') }}" class="btn btn-warning w-100">Voir les réservations</a>
                </div>
            </div>
        </div>
        <!-- Bouton de déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Se déconnecter</button>
            </form>
        @endif

        {{--  Section admin --}}
        @if(Auth::user()->role === 'admin')
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Gérer les réservations</h5>
                    <p class="text-muted small">Validez ou rejetez.</p>
                    <a href="{{ route('reservations.index') }}" class="btn btn-warning w-100 mb-2">Voir toutes les réservations</a>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'admin')
<div class="col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
        <div class="card-body text-center">
            <h5 class="card-title">Gestion des utilisateurs</h5>
            <p class="text-muted small">Créer, modifier ou supprimer des utilisateurs.</p>
            <a href="{{ route('users.index') }}" class="btn btn-success w-100 mb-2">Voir tous les utilisateurs</a>
        </div>
    </div>
</div>
        <!-- Bouton de déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Se déconnecter</button>
            </form>
       @endif

        {{--  Section étudiant --}}
        @if(Auth::user()->role === 'student')
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Disponibilité des salles</h5>
                    <p class="text-muted small">Consultez les salles déjà réservées ou disponibles.</p>
                    <a href="{{ route('reservations.index') }}" class="btn btn-success w-100">Voir les disponibilités</a>
                </div>
            </div>
        </div>
        <!-- Bouton de déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Se déconnecter</button>
            </form>
        @endif

        <!-- {{--  Section commune à tous --}}
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Les réservations</h5>
                    <p class="text-muted small">Suivez l’état des réservations actuelles.</p>
                    <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary w-100">Consulter</a>
                </div>
            </div>
            <-- Bouton de déconnexion -->
            <!-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Se déconnecter</button>
            </form>
        </div>  -->
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="card shadow">
    <div class="card-header bg-info text-white">
        <h4>Détails de la réservation</h4>
    </div>
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item"><strong>Salle :</strong> {{ $reservation->salle->nom }}</li>
            <li class="list-group-item"><strong>Matériel :</strong> {{ $reservation->materiel->nom ?? 'Aucun' }}</li>
            <li class="list-group-item"><strong>Date :</strong> {{ $reservation->date_reservation }}</li>
            <li class="list-group-item"><strong>Heure :</strong> {{ $reservation->heure_debut }} - {{ $reservation->heure_fin }}</li>
            <li class="list-group-item"><strong>Statut :</strong> {{ ucfirst($reservation->statut) }}</li>
        </ul>
    </div>
</div>
@endsection

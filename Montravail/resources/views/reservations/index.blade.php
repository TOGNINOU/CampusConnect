@extends('layouts.app')

@section('title', 'Liste des réservations')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Liste des réservations</h4>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Salle</th>
                    <th>Matériel</th>
                    <th>Date</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Statut</th>
                    @if(Auth::user()->role === 'admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->salle->nom ?? 'N/A' }}</td>
                    <td>{{ $reservation->materiel->nom ?? 'Aucun' }}</td>
                    <td>{{ $reservation->date_reservation }}</td>
                    <td>{{ $reservation->heure_debut }}</td>
                    <td>{{ $reservation->heure_fin }}</td>
                    <td>
                        @if($reservation->statut === 'acceptee')
                            <span class="badge bg-success">Acceptée</span>
                        @elseif($reservation->statut === 'refusee')
                            <span class="badge bg-danger">Refusée</span>
                        @else
                            <span class="badge bg-warning text-dark">En attente</span>
                        @endif
                    </td>

                    @if(Auth::user()->role === 'admin')
                    <td>
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('reservations.validate', $reservation->id) }}">
                                @csrf
                                <button class="btn btn-success btn-sm">✔ Valider</button>
                            </form>
                            <form method="POST" action="{{ route('reservations.reject', $reservation->id) }}">
                                @csrf
                                <button class="btn btn-danger btn-sm">✖ Rejeter</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucune réservation trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

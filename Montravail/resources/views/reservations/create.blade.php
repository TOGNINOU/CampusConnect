@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Nouvelle réservation</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Salle</label>
                <select name="salle_id" class="form-select" required>
                    @foreach($salles as $salle)
                        <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Matériel</label>
                <select name="materiel_id" class="form-select">
                    <option value="">Aucun</option>
                    @foreach($materiels as $materiel)
                        <option value="{{ $materiel->id }}">{{ $materiel->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_reservation" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Heure début</label>
                    <input type="time" name="heure_debut" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Heure fin</label>
                    <input type="time" name="heure_fin" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Réserver</button>
        </form>
    </div>
</div>
@endsection

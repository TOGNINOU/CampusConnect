@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-2xl">
    <h1 class="text-xl font-semibold mb-4">Nouvelle réservation</h1>

    @can('create', \App\Models\Reservation::class)
    <form method="POST" action="{{ route('reservations.store') }}" class="card-tilt animate-fade-in">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Type</label>
            <select name="reservable_type" class="w-full border rounded px-3 py-2 focus-ring" required>
                <option value="">-- Choisir --</option>
                <option value="room" {{ request('type')=='room' ? 'selected' : '' }}>Salle</option>
                <option value="equipment" {{ request('type')=='equipment' ? 'selected' : '' }}>Matériel</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="block text-sm">Ressource</label>
            <select name="reservable_id" class="w-full border rounded px-3 py-2 focus-ring" required>
                <optgroup label="Salles">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('type')=='room' && request('id')==$room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Matériels">
                    @foreach($equipments as $e)
                        <option value="{{ $e->id }}" {{ request('type')=='equipment' && request('id')==$e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <div class="mb-3 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm">Début</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="w-full border rounded px-3 py-2 focus-ring" required>
            </div>
            <div>
                <label class="block text-sm">Fin</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="w-full border rounded px-3 py-2 focus-ring" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="block text-sm">Notes (optionnel)</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2 focus-ring">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Réserver</button>
        </div>
    </form>
    @else
        <div class="p-4 bg-yellow-50 border rounded">
            <p class="text-sm text-gray-700">Vous n'avez pas la permission de créer une réservation. Vous pouvez consulter la disponibilité des salles.</p>
        </div>
    @endcan
</div>
@endsection

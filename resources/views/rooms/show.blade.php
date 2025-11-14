@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-2xl animate-fade-in">
    <h1 class="text-xl font-semibold">{{ $room->name }}</h1>
    <p class="text-sm text-gray-600">Capacité : {{ $room->capacity ?? '—' }} — {{ $room->location ?? 'Localisation inconnue' }}</p>
    <p class="mt-4">{{ $room->notes ?? 'Aucune note' }}</p>

    <div class="mt-6 flex items-center space-x-3">
        @can('create', App\Models\Reservation::class)
            <a href="{{ route('reservations.create') }}?type=room&id={{ $room->id }}" class="px-3 py-1 bg-green-600 text-white rounded">Réserver</a>
        @endcan
        @can('update', $room)
            <a href="{{ route('rooms.edit', $room) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Modifier</a>
            <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline-block" onsubmit="return confirm('Supprimer cette salle ?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded">Supprimer</button>
            </form>
        @endcan
        <a href="{{ route('rooms.index') }}" class="text-sm text-gray-600">Retour</a>
    </div>
</div>
@endsection

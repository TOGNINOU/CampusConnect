@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6">
        <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Salles</h1>
        @can('create', App\Models\Room::class)
            <a href="{{ route('rooms.create') }}" class="px-3 py-1 bg-indigo-600 text-white rounded">Nouvelle salle</a>
        @endcan
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($rooms as $room)
            <div class="p-4 border rounded bg-gray-50 card-tilt animate-fade-in">
                <h2 class="font-medium">{{ $room->name }}</h2>
                <p class="text-sm text-gray-600">Capacité : {{ $room->capacity ?? '—' }} — {{ $room->location ?? 'Localisation inconnue' }}</p>
                <div class="mt-2">
                    <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 text-sm">Voir</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

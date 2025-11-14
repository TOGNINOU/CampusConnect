@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-2xl animate-fade-in">
    <h1 class="text-xl font-semibold">{{ $equipment->name }}</h1>
    <p class="text-sm text-gray-600">Quantité disponible : {{ $equipment->quantity }}</p>
    <p class="mt-4">{{ $equipment->description ?? 'Aucune description' }}</p>

    <div class="mt-6 flex items-center space-x-3">
        @can('create', App\Models\Reservation::class)
            <a href="{{ route('reservations.create') }}?type=equipment&id={{ $equipment->id }}" class="px-3 py-1 bg-green-600 text-white rounded">Réserver</a>
        @endcan
        @can('update', $equipment)
            <a href="{{ route('equipments.edit', $equipment) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Modifier</a>
            <form method="POST" action="{{ route('equipments.destroy', $equipment) }}" class="inline-block" onsubmit="return confirm('Supprimer ce matériel ?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded">Supprimer</button>
            </form>
        @endcan
        <a href="{{ route('equipments.index') }}" class="text-sm text-gray-600">Retour</a>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-2xl animate-fade-in">
    <h1 class="text-xl font-semibold">Détail de la réservation</h1>
    <p class="mt-2">Ressource : {{ $reservation->reservable ? ($reservation->reservable->name ?? $reservation->reservable->title ?? class_basename($reservation->reservable_type)) : '—' }}</p>
    <p>Créée par : {{ $reservation->user->name ?? '—' }}</p>
    <p>Période : {{ $reservation->start_at->format('d/m/Y H:i') }} → {{ $reservation->end_at->format('d/m/Y H:i') }}</p>
    <p>Statut : <span class="font-medium">{{ $reservation->status }}</span></p>
    <p class="mt-4">Notes : {{ $reservation->notes ?? '—' }}</p>

    <div class="mt-6 flex items-center space-x-3">
        <a href="{{ route('reservations.index') }}" class="text-sm text-gray-600">Retour</a>
        @can('update', $reservation)
            <a href="{{ route('reservations.edit', $reservation) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Modifier</a>
        @endcan
        @can('delete', $reservation)
            <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" class="inline-block" onsubmit="return confirm('Supprimer cette réservation ?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded">Supprimer</button>
            </form>
        @endcan
    </div>
</div>
@endsection

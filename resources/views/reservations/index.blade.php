@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6">
        <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Réservations</h1>
        @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
            <a href="{{ route('reservations.create') }}" class="px-3 py-1 bg-indigo-600 text-white rounded">Nouvelle réservation</a>
        @endif
    </div>

    <div class="space-y-3">
        @foreach($reservations as $r)
            <div class="p-4 border rounded bg-gray-50 flex justify-between items-center card-tilt animate-fade-in">
                <div>
                    <div class="font-medium">{{ $r->reservable_type ? class_basename($r->reservable_type) : 'Ressource' }} — @if($r->reservable) {{ $r->reservable->name ?? $r->reservable->title ?? '—' }} @endif</div>
                    <div class="text-sm text-gray-600">{{ $r->start_at->format('d/m/Y H:i') }} → {{ $r->end_at->format('d/m/Y H:i') }}</div>
                    <div class="text-sm">Statut : <span class="font-medium">{{ $r->status }}</span></div>
                </div>
                <div class="text-right">
                    <a href="{{ route('reservations.show', $r) }}" class="text-indigo-600 text-sm">Voir</a>
                    @if(auth()->user()->isAdmin())
                        @if($r->status === \App\Models\Reservation::STATUS_PENDING)
                            <form method="POST" action="{{ route('reservations.update', $r) }}" class="mt-2">
                                @csrf
                                @method('PUT')
                                <button name="status" value="approved" class="px-2 py-1 bg-green-600 text-white rounded text-sm">Valider</button>
                                <button name="status" value="rejected" class="px-2 py-1 bg-red-600 text-white rounded text-sm">Rejeter</button>
                            </form>
                        @else
                            <div class="mt-2 text-sm text-gray-500">Traitée : <span class="font-medium">{{ $r->status }}</span>@if($r->admin) — par {{ $r->admin->name }}@endif</div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

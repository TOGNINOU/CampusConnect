@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow card-tilt animate-fade-in">
            <h3 class="text-lg font-medium mb-3">Prochains Projets</h3>
            @php
                $projects = \App\Models\Project::with('users')->orderBy('created_at','desc')->limit(5)->get();
            @endphp
            @if($projects->isEmpty())
                <p>Aucun projet pour le moment.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($projects as $p)
                        <li><a href="{{ route('projects.show', $p->id) }}" class="text-indigo-600">{{ $p->title }}</a> — <span class="text-sm text-gray-600">{{ $p->users->pluck('name')->join(', ') }}</span></li>
                    @endforeach
                </ul>
            @endif
        </div>

    <div class="bg-white p-4 rounded shadow card-tilt animate-fade-in">
            <h3 class="text-lg font-medium mb-3">Prochaines Réservations</h3>
            @php
                $reservations = \App\Models\Reservation::with('user','reservable')->orderBy('start_at')->limit(5)->get();
            @endphp
            @if($reservations->isEmpty())
                <p>Aucune réservation pour le moment.</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($reservations as $r)
                        <li>{{ $r->start_at->format('Y-m-d H:i') }} — <strong>{{ $r->reservable->name ?? 'Ressource' }}</strong> — par {{ $r->user->name ?? 'Utilisateur' }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Projets</h1>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">+ Nouveau projet</a>
    </div>

    @if($projects->isEmpty())
        <div class="p-6 bg-white rounded shadow card-tilt">Aucun projet pour le moment.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($projects as $project)
                <a href="{{ route('projects.show', $project->id) }}" class="block p-4 bg-white rounded-lg card-tilt animate-fade-in hover:shadow-lg">
                    <h3 class="text-lg font-medium text-gray-800">{{ $project->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ \Illuminate\Support\Str::limit($project->description, 120) }}</p>
                    <div class="mt-3 text-sm text-gray-400">Membres: {{ $project->users->pluck('name')->take(3)->join(', ') }}</div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

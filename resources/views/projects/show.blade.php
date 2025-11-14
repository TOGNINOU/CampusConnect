@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow card-tilt animate-fade-in">
    <h1 class="text-2xl font-semibold">{{ $project->title }}</h1>
    <p class="text-sm text-gray-600 mt-2">{{ $project->description }}</p>

    <h3 class="mt-4">Membres</h3>
    <ul class="list-disc pl-5 text-sm text-gray-700">
        @foreach($project->users as $user)
            <li class="mb-1">{{ $user->name }} <span class="text-xs text-gray-500">({{ $user->pivot->role }})</span></li>
        @endforeach
    </ul>

    <h3 class="mt-6">Livrables</h3>
    <ul class="space-y-3 mt-2">
        @foreach($project->deliverables as $d)
            <li class="flex items-center justify-between bg-gray-50 p-3 rounded">
                <div>
                    <a href="{{ route('projects.deliverables.download', [$project->id, $d->id]) }}" class="text-indigo-600 font-medium">{{ $d->filename }}</a>
                    <div class="text-xs text-gray-500">— {{ $d->uploader->name ?? 'Inconnu' }} le {{ $d->created_at->format('Y-m-d H:i') }}</div>
                </div>

                <div class="ml-4">
                    @can('delete', $project)
                        <form class="deliverable-delete-form inline" action="{{ route('projects.deliverables.destroy', [$project->id, $d->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-1 bg-red-600 text-white rounded text-sm" type="submit">Supprimer</button>
                        </form>
                    @endcan
                </div>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('projects.deliverables.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="flex items-center space-x-3">
            <input type="file" name="file" class="border rounded px-3 py-2 focus-ring">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Uploader</button>
        </div>
    </form>
    </div>

    <script>
        // Confirmation before deleting a deliverable
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.deliverable-delete-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    var ok = confirm('Confirmer la suppression du livrable ? Cette action est irréversible.');
                    if (!ok) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-sm card-tilt animate-fade-in">
    <h1 class="text-2xl font-semibold mb-4">Modifier le projet</h1>
    <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
            <input id="title" name="title" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('title', $project->title) }}" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Superviseur (enseignant)</label>
            <select id="supervisor_id" name="supervisor_id" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">-- Aucun --</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}" @if(old('supervisor_id', $project->supervisor_id) == $t->id) selected @endif>{{ $t->name }} ({{ $t->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="members" class="block text-sm font-medium text-gray-700">Membres (étudiants)</label>
            <select id="members" name="members[]" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" multiple>
                @foreach($students as $s)
                    <option value="{{ $s->id }}" @if(collect(old('members', $project->users->pluck('id')->toArray()))->contains($s->id)) selected @endif>{{ $s->name }} ({{ $s->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="report" class="block text-sm font-medium text-gray-700">Ajouter un rapport (optionnel)</label>
            <input id="report" name="report" type="file" class="mt-1 block w-full">
        </div>

        <div class="flex items-center space-x-3">
            <button class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 shadow-sm" type="submit">Enregistrer</button>
            <a href="{{ route('projects.show', $project->id) }}" class="text-sm text-gray-500">Annuler</a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-xl">
    <h1 class="text-xl font-semibold mb-4">Créer une salle</h1>

    @can('create', App\Models\Room::class)
    <form method="POST" action="{{ route('rooms.store') }}" class="card-tilt animate-fade-in">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 focus-ring" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm">Capacité</label>
            <input type="number" name="capacity" value="{{ old('capacity') }}" class="w-full border rounded px-3 py-2 focus-ring">
        </div>
        <div class="mb-3">
            <label class="block text-sm">Localisation</label>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2 focus-ring">
        </div>
        <div class="flex justify-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Créer</button>
        </div>
    </form>
    @else
        <div class="p-4 bg-yellow-50 border rounded">
            <p class="text-sm text-gray-700">Vous n'avez pas la permission de créer une salle.</p>
        </div>
    @endcan
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="bg-white shadow rounded p-6 max-w-xl">
    <h1 class="text-xl font-semibold mb-4">Ajouter du matériel</h1>

    @can('create', App\Models\Equipment::class)
    <form method="POST" action="{{ route('equipments.store') }}" class="card-tilt animate-fade-in">
        @csrf
        <div class="mb-3">
            <label class="block text-sm">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 focus-ring" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm">Quantité</label>
            <input type="number" name="quantity" value="{{ old('quantity',1) }}" class="w-full border rounded px-3 py-2 focus-ring" required>
        </div>
        <div class="flex justify-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Ajouter</button>
        </div>
    </form>
    @else
        <div class="p-4 bg-yellow-50 border rounded">
            <p class="text-sm text-gray-700">Vous n'avez pas la permission d'ajouter du matériel.</p>
        </div>
    @endcan
</div>
@endsection

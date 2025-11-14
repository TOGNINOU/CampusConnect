<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Equipment::orderBy('name')->get();
        return view('equipments.index', ['equipments' => $items]);
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Equipment::class);
        return view('equipments.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Equipment::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Equipment::create($data);
        return redirect()->route('equipments.index')->with('success', 'Matériel ajouté.');
    }

    public function show(Equipment $equipment)
    {
        // viewing is allowed for everyone (consult availability)
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment)
    {
        $this->authorize('update', $equipment);
        return view('equipments.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $this->authorize('update', $equipment);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $equipment->update($data);
        return redirect()->route('equipments.show', $equipment)->with('success', 'Matériel mis à jour.');
    }

    public function destroy(Equipment $equipment)
    {
        $this->authorize('delete', $equipment);
        $equipment->delete();
        return redirect()->route('equipments.index')->with('success', 'Matériel supprimé.');
    }
}

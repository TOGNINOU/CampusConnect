<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Salle;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // Liste des réservations
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $reservations = Reservation::with(['user', 'salle', 'materiel'])->get();
        } elseif ($user->role === 'teacher') {
            $reservations = Reservation::with(['user', 'salle', 'materiel'])
                ->where('user_id', $user->id)
                ->get();
        } else { // étudiant
            $reservations = Reservation::with(['user', 'salle', 'materiel'])
                ->where('status', 'validated')
                ->get();
        }

        return view('reservations.index', compact('reservations'));
    }

    // Formulaire de création (enseignant)
    public function create()
    {
        if(Auth::user()->role === 'teacher') {
            $salles = Salle::all();
            $materiels = Materiel::all();
            return view('reservations.create', compact('salles', 'materiels'));
        }
    }

    // Enregistrement d'une réservation (enseignant)
    public function store(Request $request)
    {
        $data = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'materiel_id' => 'nullable|exists:materiels,id',
            'date_reservation' => 'required|date|after:today',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // en attente

        Reservation::create($data);

        return redirect()->route('reservations.index')->with('success', 'Réservation créée !');
    }

    // Modification (enseignant avant validation ou admin à tout moment)
    public function update(Request $request, Reservation $reservation)
    {
        $user = Auth::user();

        if ($user->role === 'teacher' && $reservation->user_id !== $user->id) {
            abort(403, "Vous ne pouvez modifier que vos propres réservations.");
        }

        if ($user->role === 'teacher' && $reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'Impossible de modifier une réservation déjà validée.');
        }

        $data = $request->validate([
            'salle_id' => 'sometimes|exists:salles,id',
            'materiel_id' => 'nullable|exists:materiels,id',
            'date_reservation' => 'sometimes|date|after:today',
            'heure_debut' => 'sometimes',
            'heure_fin' => 'sometimes|after:heure_debut',
            'status' => 'sometimes|in:pending,validated,rejected', // admin peut changer le status
        ]);

        $reservation->update($data);

        return redirect()->route('reservations.index')->with('success', 'Réservation mise à jour !');
    }

    // Validation (admin seulement)
    public function validateReservation(Reservation $reservation)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $reservation->update(['status' => 'validated']);

        return redirect()->back()->with('success', 'Réservation validée !');
    }

    // Rejeter une réservation (admin seulement)
    public function rejectReservation(Reservation $reservation)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $reservation->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Réservation rejetée !');
    }

    // Affichage d'une réservation individuelle (facultatif)
    public function show(Reservation $reservation)
    {
        $user = Auth::user();

        if ($user->role === 'student' && $reservation->status !== 'validated') {
            abort(403);
        }

        return view('reservations.show', compact('reservation'));
    }
}

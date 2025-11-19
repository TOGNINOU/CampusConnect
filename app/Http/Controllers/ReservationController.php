<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    $user = Auth::user();
        if ($user->isAdmin()) {
            // Admins see everything
            $reservations = Reservation::with(['user','reservable'])->orderBy('start_at')->get();
        } else {
            // Non-admins see all approved reservations and their own reservations (so teachers see their pending requests)
            $reservations = Reservation::with(['user','reservable'])
                ->where(function ($q) use ($user) {
                    $q->where('status', 'approved')
                      ->orWhere('user_id', $user->id);
                })
                ->orderBy('start_at')
                ->get();
        }

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        // only authorized users (teachers/admins) can access the create form
        $this->authorize('create', \App\Models\Reservation::class);

        // simple form expects reservable_type (room/equipment) and reservable_id
        $rooms = Room::orderBy('name')->get();
        $equipments = Equipment::orderBy('name')->get();
        return view('reservations.create', compact('rooms','equipments'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Reservation::class);
        $data = $request->validate([
            'reservable_type' => 'required|string|in:room,equipment',
            'reservable_id' => 'required|integer',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'notes' => 'nullable|string',
        ]);

        $class = $this->resolveReservableClass($data['reservable_type']);
        $reservable = $class::findOrFail($data['reservable_id']);

        $start = $data['start_at'];
        $end = $data['end_at'];

        $conflict = Reservation::where('reservable_type', $class)
            ->where('reservable_id', $reservable->id)
            ->whereIn('status', [\App\Models\Reservation::STATUS_PENDING, \App\Models\Reservation::STATUS_APPROVED])
            ->overlapping($start, $end)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_at' => 'La période choisie est déjà réservée.'])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'reservable_type' => $class,
            'reservable_id' => $reservable->id,
            'start_at' => $start,
            'end_at' => $end,
            'status' => \App\Models\Reservation::STATUS_PENDING,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Réservation créée, en attente de validation.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Return reservation events as JSON for FullCalendar.
     */
    public function events(Request $request)
    {
        $roomId = $request->query('room_id');

        $user = Auth::user();

        // Base query for room reservations
        $query = Reservation::query()->where('reservable_type', \App\Models\Room::class);

        // By default, show only approved reservations
        $query->where('status', 'approved');

        // If the user is an admin, include pending as well
        if ($user && $user->isAdmin()) {
            $query = Reservation::query()->where('reservable_type', \App\Models\Room::class)
                ->whereIn('status', ['pending', 'approved']);
        }

        // If the user is the owner, include their own reservations (so teachers see their pending ones)
        if ($user && ! $user->isAdmin()) {
            $query = Reservation::query()->where('reservable_type', \App\Models\Room::class)
                ->where(function ($q) use ($user) {
                    $q->where('status', 'approved')
                      ->orWhere('user_id', $user->id);
                });
        }

        if ($roomId) {
            $query->where('reservable_id', $roomId);
        }

        $events = $query->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => ($r->status === 'approved' ? 'Validé' : 'En attente') . ' — ' . ($r->user->name ?? 'Utilisateur'),
                'start' => $r->start_at->toIso8601String(),
                'end' => $r->end_at->toIso8601String(),
                'color' => $r->status === 'approved' ? '#10B981' : '#F59E0B',
            ];
        });

        return response()->json($events);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('approve', $reservation);

        $user = Auth::user();
        $data = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Only allow changing status if reservation is still pending
        if ($reservation->status !== \App\Models\Reservation::STATUS_PENDING) {
            return redirect()->route('reservations.index')->with('warning', 'Cette réservation a déjà été traitée.');
        }

        $reservation->status = $data['status'];
        $reservation->admin_id = $user->id;
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Statut mis à jour.');
    }

    protected function resolveReservableClass(string $type): string
    {
        return $type === 'room' ? Room::class : Equipment::class;
    }

    
}

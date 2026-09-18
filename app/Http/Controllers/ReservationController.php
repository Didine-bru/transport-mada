<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with([
            'user',
            'trip',
            'trip.transporter',
            'trip.vehicle',
        ])->orderBy('created_at', 'desc')->get();

        $users = User::whereIn('role', ['client', 'agent'])->orderBy('name')->get();

        $trips = Trip::with([
            'transporter',
            'vehicle',
        ])
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
            'users' => $users,
            'trips' => $trips,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'trip_id' => ['required', 'exists:trips,id'],
            'seats' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $trip = Trip::where('id', $validated['trip_id'])->lockForUpdate()->firstOrFail();

            if ($trip->status !== 'scheduled') {
                abort(422, 'Ce trajet n’est pas disponible.');
            }

            if ($validated['seats'] > $trip->available_seats) {
                abort(422, 'Le nombre de places demandé dépasse les places disponibles.');
            }

            $totalAmount = $trip->price * $validated['seats'];

            Reservation::create([
                'user_id' => $validated['user_id'],
                'trip_id' => $trip->id,
                'reservation_number' => 'RES-' . strtoupper(uniqid()),
                'seats' => $validated['seats'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            $trip->decrement('available_seats', $validated['seats']);
        });

        return redirect()->route('reservations.index')->with('success', 'Réservation créée avec succès.');
    }
    public function update(Request $request, Reservation $reservation)
{
    $validated = $request->validate([
        'user_id' => ['required', 'exists:users,id'],
        'trip_id' => ['required', 'exists:trips,id'],
        'seats' => ['required', 'integer', 'min:1'],
    ]);

    DB::transaction(function () use ($validated, $reservation) {
        // Verrouiller l'ancienne réservation
        $reservation->lockForUpdate();

        // Verrouiller le trajet sélectionné
        $newTrip = Trip::where('id', $validated['trip_id'])
            ->lockForUpdate()
            ->firstOrFail();

        if ($newTrip->status !== 'scheduled') {
            abort(422, 'Ce trajet n’est pas disponible.');
        }

        /*
         * Si le trajet reste le même :
         * on récupère d'abord les anciennes places,
         * puis on vérifie les nouvelles places demandées.
         */
        if ($reservation->trip_id == $newTrip->id) {
            $availableSeatsAfterReturn =
                $newTrip->available_seats + $reservation->seats;

            if ($validated['seats'] > $availableSeatsAfterReturn) {
                abort(
                    422,
                    'Le nombre de places demandé dépasse les places disponibles.'
                );
            }

            // On remet les anciennes places
            $newTrip->increment('available_seats', $reservation->seats);

            // Puis on retire les nouvelles places
            $newTrip->decrement('available_seats', $validated['seats']);
        } else {
            /*
             * Le trajet change :
             * 1. On remet les anciennes places sur l'ancien trajet
             * 2. On retire les nouvelles places du nouveau trajet
             */

            $oldTrip = Trip::where('id', $reservation->trip_id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldTrip->increment('available_seats', $reservation->seats);

            if ($validated['seats'] > $newTrip->available_seats) {
                abort(
                    422,
                    'Le nombre de places demandé dépasse les places disponibles.'
                );
            }

            $newTrip->decrement('available_seats', $validated['seats']);
        }

        // Recalcul du montant
        $totalAmount = $newTrip->price * $validated['seats'];

        $reservation->update([
            'user_id' => $validated['user_id'],
            'trip_id' => $newTrip->id,
            'seats' => $validated['seats'],
            'total_amount' => $totalAmount,
        ]);
    });

    return redirect()
        ->route('reservations.index')
        ->with('success', 'Réservation modifiée avec succès.');
}
public function destroy(Reservation $reservation)
{
    DB::transaction(function () use ($reservation) {
        $reservation->lockForUpdate();

        $trip = Trip::where('id', $reservation->trip_id)
            ->lockForUpdate()
            ->first();

        if ($trip) {
            $trip->increment('available_seats', $reservation->seats);
        }

        $reservation->delete();
    });

    return redirect()
        ->route('reservations.index')
        ->with('success', 'Réservation supprimée avec succès.');
}
}

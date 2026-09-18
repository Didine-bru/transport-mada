<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::with(['transporter', 'vehicle'])
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        $transporters = Transporter::where('is_active', true)
            ->orderBy('company_name')
            ->get();

        $vehicles = Vehicle::where('is_active', true)
            ->with('transporter')
            ->orderBy('registration_number')
            ->get();

        return Inertia::render('Trips/Index', [
            'trips' => $trips,
            'transporters' => $transporters,
            'vehicles' => $vehicles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transporter_id' => ['required', 'exists:transporters,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'departure_city' => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:255'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,cancelled,completed'],
        ]);

        Trip::create($validated);

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trajet créé avec succès.');
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'transporter_id' => ['required', 'exists:transporters,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'departure_city' => ['required', 'string', 'max:255'],
            'destination_city' => ['required', 'string', 'max:255'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'available_seats' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,cancelled,completed'],
        ]);

        $trip->update($validated);

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trajet modifié avec succès.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();

        return redirect()
            ->route('trips.index')
            ->with('success', 'Trajet supprimé avec succès.');
    }
}
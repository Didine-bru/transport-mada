<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('transporter')
            ->orderBy('created_at', 'desc')
            ->get();

        $transporters = Transporter::orderBy('company_name')
            ->get();

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'transporters' => $transporters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transporter_id' => [
                'required',
                'exists:transporters,id',
            ],
            'registration_number' => [
                'required',
                'string',
                'max:20',
                'unique:vehicles,registration_number',
            ],
            'brand' => [
                'required',
                'string',
                'max:255',
            ],
            'model' => [
                'required',
                'string',
                'max:255',
            ],
            'seats' => [
                'required',
                'integer',
                'min:1',
            ],
            'year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        Vehicle::create($validated);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Véhicule créé avec succès.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'transporter_id' => [
                'required',
                'exists:transporters,id',
            ],
            'registration_number' => [
                'required',
                'string',
                'max:20',
                'unique:vehicles,registration_number,' . $vehicle->id,
            ],
            'brand' => [
                'required',
                'string',
                'max:255',
            ],
            'model' => [
                'required',
                'string',
                'max:255',
            ],
            'seats' => [
                'required',
                'integer',
                'min:1',
            ],
            'year' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        $vehicle->update($validated);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Véhicule modifié avec succès.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Véhicule supprimé avec succès.');
    }
}
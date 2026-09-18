<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransporterController extends Controller
{
    /**
     * Afficher la liste des transporteurs
     */
    public function index()
    {
        $transporters = Transporter::orderBy('created_at', 'desc')->get();

        return Inertia::render('Transporters/Index', [
            'transporters' => $transporters,
        ]);
    }

    /**
     * Enregistrer un nouveau transporteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'manager_name' => ['required', 'string', 'max:255'],
            'cin' => ['required', 'digits:12', 'unique:transporters,cin'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        Transporter::create($validated);

        return redirect()
            ->route('transporters.index')
            ->with('success', 'Transporteur créé avec succès.');
    }

    /**
     * Modifier un transporteur
     */
    public function update(Request $request, Transporter $transporter)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'manager_name' => ['required', 'string', 'max:255'],
            'cin' => [
                'required',
                'digits:12',
                'unique:transporters,cin,' . $transporter->id,
            ],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $transporter->update($validated);

        return redirect()
            ->route('transporters.index')
            ->with('success', 'Transporteur modifié avec succès.');
    }

    /**
     * Supprimer un transporteur
     */
    public function destroy(Transporter $transporter)
    {
        $transporter->delete();

        return redirect()
            ->route('transporters.index')
            ->with('success', 'Transporteur supprimé avec succès.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $trips = Trip::with([
            'transporter',
            'vehicle',
        ])
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->orderBy('departure_time')
            ->get();

        return Inertia::render('Welcome', [
            'trips' => $trips,
        ]);
    }
}
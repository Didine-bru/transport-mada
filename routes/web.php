<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TransporterController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes users
Route::get('/users', [UserController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('users.index');

Route::get('/users/create', [UserController::class, 'create'])->middleware(['auth', 'verified', 'admin'])->name(
    'users.create',
);

Route::post('/users', [UserController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('users.store');

Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['auth', 'verified', 'admin'])->name(
    'users.update',
);

// routes transporters
Route::get('/transporters', [TransporterController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name(
    'transporters.index',
);
Route::post('/transporters', [TransporterController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('transporters.store');

Route::put('/transporters/{transporter}', [TransporterController::class, 'update'])->middleware([
    'auth',
    'verified',
    'admin',
])->name('transporters.update');

Route::delete('/transporters/{transporter}', [TransporterController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('transporters.destroy');

// routes vehicles
Route::get('/vehicles', [VehicleController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name(
    'vehicles.index',
);
Route::post('/vehicles', [VehicleController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('vehicles.store');

Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->middleware([
    'auth',
    'verified',
    'admin',
])->name('vehicles.update');

Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('vehicles.destroy');

//routes trips
Route::resource('trips', TripController::class)->only(['index', 'store', 'update', 'destroy']);

Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('users.destroy');

//routes reservations
Route::resource('reservations', ReservationController::class)->only(['index', 'store', 'update', 'destroy']);

Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'transporter_id',
        'vehicle_id',
        'departure_city',
        'destination_city',
        'departure_date',
        'departure_time',
        'price',
        'available_seats',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'price' => 'decimal:2',
        'available_seats' => 'integer',
    ];

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Transporter::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
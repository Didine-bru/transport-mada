<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'transporter_id',
        'registration_number',
        'brand',
        'model',
        'seats',
        'year',
        'is_active',
    ];

    protected $casts = [
        'seats' => 'integer',
        'year' => 'integer',
        'is_active' => 'boolean',
    ];

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Transporter::class);
    }
}
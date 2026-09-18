<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            // Transporteur responsable du trajet
            $table->foreignId('transporter_id')->constrained('transporters')->cascadeOnDelete();

            // Véhicule utilisé pour le trajet
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();

            // Informations du trajet
            $table->string('departure_city');
            $table->string('destination_city');

            // Date et heure prévues du départ
            $table->date('departure_date');
            $table->time('departure_time');

            // Prix d'une place
            $table->decimal('price', 12, 2);

            // Nombre total de places disponibles au départ
            $table->unsignedInteger('available_seats');

            // Statut du trajet
            $table->string('status')->default('scheduled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};

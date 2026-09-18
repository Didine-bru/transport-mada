<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('trip_id')
            ->constrained('trips')
            ->cascadeOnDelete();

        $table->string('reservation_number')->unique();

        $table->unsignedInteger('seats');

        $table->decimal('total_amount', 12, 2);

        $table->string('status')->default('pending');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropForeign(['trip_id']);

        $table->dropColumn([
            'user_id',
            'trip_id',
            'reservation_number',
            'seats',
            'total_amount',
            'status',
        ]);
    });
}
};

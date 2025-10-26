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
        Schema::create('price_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('season_id')->nullable()->constrained()->nullOnDelete(); // null = regla global
            $table->unsignedTinyInteger('dow')->nullable();     // 1..7 (L..D), null = todos
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedTinyInteger('min_nights')->default(1);
            $table->decimal('cleaning_fee', 10, 2)->default(0);
            $table->unsignedTinyInteger('included_guests')->default(2);
            $table->decimal('extra_guest_fee', 10, 2)->default(0);
            $table->boolean('is_override')->default(false);     // prioridad máxima
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};

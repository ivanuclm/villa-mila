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
        Schema::create('amenities', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->timestamps();
        });
        Schema::create('amenity_listing', function (Blueprint $t) {
            $t->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $t->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $t->primary(['listing_id','amenity_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};

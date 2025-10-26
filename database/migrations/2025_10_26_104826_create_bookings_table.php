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
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $t->string('customer_name');     // simple por ahora
            $t->string('customer_email');    // ya refinaremos con Customers
            $t->date('arrival');
            $t->date('departure');
            $t->unsignedTinyInteger('guests')->default(2);
            $t->enum('status',['pending','hold','confirmed','cancelled'])->default('pending');
            $t->decimal('total',10,2)->default(0);  // precio simple por ahora
            $t->string('source')->default('web');   // web, manual, airbnb_ical (más tarde)
            $t->timestamps();
            $t->index(['listing_id','arrival','departure']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

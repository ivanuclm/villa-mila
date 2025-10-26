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
        // Schema::create('listings', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('listings', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->json('description')->nullable(); // translatable
            $t->string('license_number')->nullable(); // VUT
            $t->string('address')->nullable();
            $t->decimal('lat',10,7)->nullable();
            $t->decimal('lng',10,7)->nullable();
            $t->unsignedTinyInteger('max_guests')->default(4);
            $t->time('checkin_from')->nullable();
            $t->time('checkout_until')->nullable();
            $t->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

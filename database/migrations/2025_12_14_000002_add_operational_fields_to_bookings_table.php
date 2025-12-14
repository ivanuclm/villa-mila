<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('total');
            $table->timestamp('payment_received_at')->nullable()->after('payment_method');
            $table->text('payment_notes')->nullable()->after('payment_received_at');
            $table->json('operations_checklist')->nullable()->after('payment_notes');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_received_at',
                'payment_notes',
                'operations_checklist',
            ]);
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->uuid('public_access_token')->nullable()->unique()->after('source');
            $table->timestamp('portal_last_accessed_at')->nullable()->after('public_access_token');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['public_access_token', 'portal_last_accessed_at']);
        });
    }
};

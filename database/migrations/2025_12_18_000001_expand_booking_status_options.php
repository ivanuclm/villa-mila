<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check");
            DB::statement("
                ALTER TABLE bookings
                ADD CONSTRAINT bookings_status_check
                CHECK (status IN ('pending','hold','confirmed','in_stay','completed','cancelled'))
            ");

            return;
        }

        DB::statement("
            ALTER TABLE bookings
            MODIFY status ENUM('pending','hold','confirmed','in_stay','completed','cancelled') DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE bookings DROP CONSTRAINT IF EXISTS bookings_status_check");
            DB::statement("
                ALTER TABLE bookings
                ADD CONSTRAINT bookings_status_check
                CHECK (status IN ('pending','hold','confirmed','cancelled'))
            ");

            return;
        }

        DB::statement("
            ALTER TABLE bookings
            MODIFY status ENUM('pending','hold','confirmed','cancelled') DEFAULT 'pending'
        ");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('cadastral_reference')->nullable()->after('contract_full_text');
            $table->string('municipal_registry_code')->nullable()->after('cadastral_reference');
            $table->string('clm_registry_number')->nullable()->after('municipal_registry_code');
            $table->string('nra_registry_number')->nullable()->after('clm_registry_number');
            $table->string('ccaa_license_code')->nullable()->after('nra_registry_number');
            $table->unsignedTinyInteger('extra_beds')->default(0)->after('ccaa_license_code');
            $table->unsignedTinyInteger('cribs')->default(0)->after('extra_beds');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'cadastral_reference',
                'municipal_registry_code',
                'clm_registry_number',
                'nra_registry_number',
                'ccaa_license_code',
                'extra_beds',
                'cribs',
            ]);
        });
    }
};

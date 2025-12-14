<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('contract_owner_name')->nullable()->after('cover_image_path');
            $table->string('contract_owner_document')->nullable()->after('contract_owner_name');
            $table->string('contract_owner_address')->nullable()->after('contract_owner_document');
            $table->longText('contract_full_text')->nullable()->after('contract_owner_address');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'contract_owner_name',
                'contract_owner_document',
                'contract_owner_address',
                'contract_full_text',
            ]);
        });
    }
};

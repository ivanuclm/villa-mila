<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_guests', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('booking_id');
            $table->string('first_surname')->nullable()->after('first_name');
            $table->string('second_surname')->nullable()->after('first_surname');
            $table->string('document_type')->nullable()->after('document_number');
            $table->string('document_support_number')->nullable()->after('document_type');
            $table->string('gender')->nullable()->after('document_support_number');
            $table->string('birth_country')->nullable()->after('birthdate');
            $table->boolean('is_minor')->default(false)->after('birth_country');
            $table->string('kinship')->nullable()->after('is_minor');
        });
    }

    public function down(): void
    {
        Schema::table('booking_guests', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'first_surname',
                'second_surname',
                'document_type',
                'document_support_number',
                'gender',
                'birth_country',
                'is_minor',
                'kinship',
            ]);
        });
    }
};

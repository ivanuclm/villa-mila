<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_first_name')->nullable()->after('customer_name');
            $table->string('customer_first_surname')->nullable()->after('customer_first_name');
            $table->string('customer_second_surname')->nullable()->after('customer_first_surname');
            $table->string('customer_document_type')->nullable()->after('customer_second_surname');
            $table->string('customer_document_number')->nullable()->after('customer_document_type');
            $table->string('customer_document_support_number')->nullable()->after('customer_document_number');
            $table->date('customer_birthdate')->nullable()->after('customer_document_support_number');
            $table->string('customer_birth_country')->nullable()->after('customer_birthdate');
            $table->string('customer_address_street')->nullable()->after('customer_birth_country');
            $table->string('customer_address_number')->nullable()->after('customer_address_street');
            $table->string('customer_address_city')->nullable()->after('customer_address_number');
            $table->string('customer_address_province')->nullable()->after('customer_address_city');
            $table->string('customer_address_country')->nullable()->after('customer_address_province');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'customer_first_name',
                'customer_first_surname',
                'customer_second_surname',
                'customer_document_type',
                'customer_document_number',
                'customer_document_support_number',
                'customer_birthdate',
                'customer_birth_country',
                'customer_address_street',
                'customer_address_number',
                'customer_address_city',
                'customer_address_province',
                'customer_address_country',
            ]);
        });
    }
};

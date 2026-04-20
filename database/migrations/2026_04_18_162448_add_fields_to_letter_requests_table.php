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
        Schema::table('letter_requests', function (Blueprint $table) {
            
        // Purpose (dropdown)
        $table->string('purpose_type')->nullable();

        // Address info
        $table->text('address')->nullable();
        $table->string('address_accuracy')->default('exact');

        // GPS location
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_requests', function (Blueprint $table) {
            //
              $table->dropColumn([
            'purpose_type',
            'address',
            'address_accuracy',
            'latitude',
            'longitude'
        ]);
        });
    }
};

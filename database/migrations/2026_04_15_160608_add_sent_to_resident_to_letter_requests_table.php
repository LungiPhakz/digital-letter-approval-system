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
            //
            $table->boolean('sent_to_resident')->default(false);
            $table->timestamp('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_requests', function (Blueprint $table) {
            //
             $table->dropColumn(['sent_to_resident', 'sent_at']);
        });
    }
};

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
        Schema::create('letters', function (Blueprint $table) {
          $table->id();

    $table->foreignId('request_id')
          ->constrained('letter_requests')
          ->cascadeOnDelete();

    $table->string('reference_number');

    $table->foreignId('approved_by') // 🔥 FIXED
          ->constrained('users')
          ->cascadeOnDelete();

    $table->string('qr_code');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};

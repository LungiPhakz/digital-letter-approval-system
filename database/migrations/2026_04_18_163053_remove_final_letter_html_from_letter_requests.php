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
             // ✅ Only drop if columns exist (prevents errors)
            if (Schema::hasColumn('letter_requests', 'final_letter_html')) {
                $table->dropColumn('final_letter_html');
            }
            if (Schema::hasColumn('letter_requests', 'final_letter')) {
                $table->dropColumn('final_letter');
            }

            if (Schema::hasColumn('letter_requests', 'sent_to_resident')) {
                $table->dropColumn('sent_to_resident');
            }

            if (Schema::hasColumn('letter_requests', 'sent_at')) {
                $table->dropColumn('sent_at');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_requests', function (Blueprint $table) {
            //
             // Optional: restore them if rollback happens
            $table->text('final_letter_html')->nullable();
            $table->text('final_letter')->nullable();
            $table->boolean('sent_to_resident')->default(false);
            $table->timestamp('sent_at')->nullable();
        });
    }
};
